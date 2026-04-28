<?php

namespace App\Jobs;

use App\Models\Game;
use App\Models\PriceNotification;
use App\Models\Wishlist;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckWishlistPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Timeout generoso porque hace N llamadas a Steam API */
    public int $timeout = 600;

    public function handle(GameService $gameService): void
    {
        // Obtener todos los appids únicos que están en alguna wishlist
        $appids = Wishlist::pluck('appid')->unique()->values()->all();

        if (empty($appids)) {
            Log::info('[CheckWishlistPrices] No hay juegos en wishlists.');
            return;
        }

        Log::info('[CheckWishlistPrices] Revisando ' . count($appids) . ' juegos.');

        foreach ($appids as $appid) {
            $this->checkGame((int) $appid, $gameService);
        }

        Log::info('[CheckWishlistPrices] Revisión completada.');
    }

    private function checkGame(int $appid, GameService $gameService): void
    {
        try {
            $steamData = $gameService->GetDetails($appid);
            $data      = $steamData['data'] ?? null;

            if (! $data) {
                return;
            }

            $priceOverview  = $data['price_overview'] ?? null;
            $currentPrice   = $priceOverview['final']           ?? 0;
            $basePrice      = $priceOverview['initial']         ?? 0;
            $discount       = $priceOverview['discount_percent']?? 0;
            $name           = $data['name']         ?? "Game #{$appid}";
            $image          = $data['header_image'] ?? null;

            // Si no hay precio base disponible desde Steam, no podemos comparar
            if ($basePrice === 0) {
                return;
            }

            /** @var Game|null $game */
            $game = Game::find($appid);

            // Si no tenemos el juego guardado todavía, lo guardamos como baseline y salimos
            if (! $game) {
                Game::updateOrCreate(
                    ['appid' => $appid],
                    [
                        'name'             => $name,
                        'last_updated_at'  => now(),
                        'price'            => $currentPrice,
                        'base_price'       => $basePrice,
                        'discount_percent' => $discount,
                        'image'            => $image,
                        'is_free'          => $data['is_free'] ?? false,
                    ]
                );
                return;
            }

            $storedBasePrice = $game->base_price > 0 ? $game->base_price : $basePrice;

            // ¿Bajó el precio respecto al precio base?
            $priceDrop = $currentPrice < $storedBasePrice;

            if ($priceDrop) {
                // Obtener todos los usuarios que tienen este juego en su wishlist
                $userIds = Wishlist::where('appid', $appid)->pluck('user_id');
                $users   = User::whereIn('id', $userIds)->get();

                foreach ($users as $user) {
                    // Evitar duplicar notificaciones: no crear si ya existe una sin leer para este juego/precio
                    $alreadyNotified = PriceNotification::where('user_id', $user->id)
                        ->where('appid', $appid)
                        ->where('new_price', $currentPrice)
                        ->whereNull('read_at')
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    $notification = PriceNotification::create([
                        'user_id'          => $user->id,
                        'appid'            => $appid,
                        'game_name'        => $name,
                        'game_image'       => $image,
                        'old_price'        => $storedBasePrice,
                        'new_price'        => $currentPrice,
                        'discount_percent' => $discount,
                    ]);

                    Log::info("[CheckWishlistPrices] Notificación creada: {$name} para user {$user->id} ({$storedBasePrice} → {$currentPrice})");
                }
            }

            // Actualizar los datos del juego en BD con los valores actuales
            $game->update([
                'name'             => $name,
                'last_updated_at'  => now(),
                'price'            => $currentPrice,
                'base_price'       => $storedBasePrice,
                'discount_percent' => $discount,
                'image'            => $image,
                'is_free'          => $data['is_free'] ?? false,
            ]);

        } catch (\Throwable $e) {
            Log::error("[CheckWishlistPrices] Error al revisar appid {$appid}: " . $e->getMessage());
        }
    }
}
