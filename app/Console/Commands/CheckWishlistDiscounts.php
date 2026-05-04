<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\PriceNotification;
use App\Models\Wishlist;
use App\Services\GameService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckWishlistDiscounts extends Command
{
    /**
     * Umbral mínimo de descuento para crear una notificación (%).
     */
    private const DISCOUNT_THRESHOLD = 50;

    protected $signature   = 'wishlist:check-discounts
                                {--threshold=50 : Porcentaje mínimo de descuento para notificar}
                                {--force         : Ignorar caché y consultar Steam directamente}';

    protected $description = 'Revisa los juegos de todas las wishlists y crea notificaciones para descuentos altos (≥50% por defecto).';

    public function __construct(private readonly GameService $gameService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $threshold = (int) $this->option('threshold');
        $force     = (bool) $this->option('force');

        $this->info("🔍 Comprobando descuentos ≥{$threshold}% en wishlists...");

        // Obtener todos los appids únicos que están en alguna wishlist.
        $wishlistAppids = Wishlist::distinct()->pluck('appid')->map(fn ($id) => (int) $id)->toArray();

        if (empty($wishlistAppids)) {
            $this->warn('No hay juegos en ninguna wishlist.');
            return self::SUCCESS;
        }

        $notificationsCreated = 0;
        $bar = $this->output->createProgressBar(count($wishlistAppids));
        $bar->start();

        foreach ($wishlistAppids as $appid) {
            try {
                // Primero intentar desde la BD local (más rápido).
                $game = Game::find($appid);

                $discount = 0;
                $price    = 0;
                $basePrice = 0;
                $name     = "Game #{$appid}";
                $image    = null;
                $isFree   = false;

                if ($game && $game->last_updated_at && $game->last_updated_at->gt(now()->subHours(6)) && ! $force) {
                    // Datos frescos de BD (< 6h), usar directamente.
                    $discount  = $game->discount_percent ?? 0;
                    $price     = $game->price ?? 0;
                    $basePrice = $game->base_price ?? $price;
                    $name      = $game->name;
                    $image     = $game->image;
                    $isFree    = $game->is_free;
                } else {
                    // Consultar Steam.
                    $steamData = $this->gameService->GetDetails($appid);
                    $data      = $steamData['data'] ?? null;

                    if (! $data) {
                        $bar->advance();
                        continue;
                    }

                    $priceOverview = $data['price_overview'] ?? null;
                    $discount      = $priceOverview['discount_percent'] ?? 0;
                    $price         = $priceOverview['final'] ?? 0;
                    $basePrice     = $priceOverview['initial'] ?? 0;
                    $name          = $data['name'] ?? "Game #{$appid}";
                    $image         = $data['header_image'] ?? null;
                    $isFree        = $data['is_free'] ?? false;

                    // Actualizar BD local con datos frescos.
                    Game::updateOrCreate(
                        ['appid' => $appid],
                        [
                            'name'             => $name,
                            'last_updated_at'  => now(),
                            'price'            => $price,
                            'base_price'       => $basePrice > 0 ? $basePrice : $price,
                            'discount_percent' => $discount,
                            'image'            => $image,
                            'is_free'          => $isFree,
                        ]
                    );
                }

                // Saltar si no cumple el umbral o es gratis.
                if ($discount < $threshold || $isFree) {
                    $bar->advance();
                    continue;
                }

                // Para cada usuario que tiene este juego en su wishlist, crear notificación.
                $userIds = Wishlist::where('appid', $appid)->pluck('user_id');

                foreach ($userIds as $userId) {
                    $alreadyExists = PriceNotification::where('user_id', $userId)
                        ->where('appid', $appid)
                        ->where('created_at', '>=', now()->subHours(24))
                        ->exists();

                    if ($alreadyExists) {
                        continue;
                    }

                    PriceNotification::create([
                        'user_id'          => $userId,
                        'appid'            => $appid,
                        'game_name'        => $name,
                        'game_image'       => $image,
                        'old_price'        => $basePrice > 0 ? $basePrice : $price,
                        'new_price'        => $price,
                        'discount_percent' => $discount,
                        'read_at'          => null,
                    ]);

                    $notificationsCreated++;
                }
            } catch (\Throwable $e) {
                Log::warning("CheckWishlistDiscounts: error en appid {$appid}", [
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Listo. Notificaciones creadas: {$notificationsCreated}");

        return self::SUCCESS;
    }
}
