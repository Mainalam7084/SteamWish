<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Wishlist;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

require_once app_path() . '/Includes/steam_wrapper.php';

class WishlistController
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Devuelve los IDs de los juegos en la wishlist del usuario.
     *
     * @return JsonResponse
     */
    public function ids(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        return response()->json(
            Auth::user()->wishlists()->pluck('appid')->map(fn ($id) => (int) $id)->values()
        );
    }

    /**
     * Devuelve una vista previa de los últimos juegos en la wishlist.
     *
     * @return JsonResponse
     */
    public function preview(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        // Obtener los últimos 3 juegos de la wishlist.
        $appids = Auth::user()
            ->wishlists()
            ->latest()
            ->take(3)
            ->pluck('appid')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        // Buscar juegos en la base de datos local.
        $dbGames = Game::whereIn('appid', $appids)
            ->get()
            ->keyBy('appid');

        $games = [];
        foreach ($appids as $appid) {
            if (isset($dbGames[$appid]) && $dbGames[$appid]->name) {
                // Usar datos de la base de datos local.
                $g = $dbGames[$appid];
                $games[] = [
                    'appid' => $g->appid,
                    'name'  => $g->name,
                    'image' => $g->image ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                ];
            } else {
                // Obtener datos de Steam si no están en la DB.
                $steamData = $this->gameService->GetDetails($appid)['data'] ?? null;
                $games[] = [
                    'appid' => $appid,
                    'name'  => $steamData['name'] ?? "Game #{$appid}",
                    'image' => $steamData['header_image'] ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                ];
            }
        }

        return response()->json($games);
    }

    /**
     * Muestra la lista completa de juegos en la wishlist del usuario.
     *
     * @return View
     */
    public function index(): View
    {
        $user    = Auth::user();
        $appids  = $user->wishlists()->pluck('appid')->map(fn ($id) => (int) $id)->toArray();

        if (empty($appids)) {
            return view('pages.wishlist', ['games' => []]);
        }

        // Buscar juegos en la base de datos local.
        $dbGames = Game::whereIn('appid', $appids)
            ->get()
            ->keyBy('appid');

        $games = [];

        foreach ($appids as $appid) {
            if (isset($dbGames[$appid]) && $dbGames[$appid]->name) {
                // Usar datos de la base de datos local.
                $g = $dbGames[$appid];
                $games[] = [
                    'appid'    => $g->appid,
                    'name'     => $g->name,
                    'image'    => $g->image ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                    'price'    => $g->is_free ? 'Free' : ($g->price ? number_format($g->price, 2) . '€' : '—'),
                    'discount' => $g->discount_percent,
                    'is_free'  => $g->is_free,
                ];
            } else {
                // Obtener datos de Steam si no están en la DB.
                $steamData = $this->gameService->GetDetails($appid);
                $data = $steamData['data'] ?? null;

                if ($data) {
                    $games[] = [
                        'appid'    => $appid,
                        'name'     => $data['name'] ?? "Game #{$appid}",
                        'image'    => $data['header_image'] ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                        'price'    => isset($data['price_overview']['final_formatted']) ? $data['price_overview']['final_formatted'] : ('Free'),
                        'discount' => isset($data['price_overview']['discount_percent']) ? $data['price_overview']['discount_percent'] : 0,
                        'is_free'  => $data['is_free'] ?? false,
                    ];
                } else {
                    $games[] = [
                        'appid'    => $appid,
                        'name'     => "Game #{$appid}",
                        'image'    => "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                        'price'    => '—',
                        'discount' => 0,
                        'is_free'  => false,
                    ];
                }
            }
        }

        return view('pages.wishlist', compact('games'));
    }

    /**
     * Agrega o elimina un juego de la wishlist del usuario.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate(['appid' => 'required|string']);
        $user  = Auth::user();
        $appid = $request->input('appid');

        // Verificar si el juego ya está en la wishlist.
        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('appid', $appid)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create(['user_id' => $user->id, 'appid' => $appid]);

        // Guardar/actualizar datos del juego para tracking de precios.
        try {
            $steamData = $this->gameService->GetDetails($appid);
            $data = $steamData['data'] ?? null;

            if ($data) {
                $priceOverview = $data['price_overview'] ?? null;
                $price         = $priceOverview['final']    ?? 0;
                $basePrice     = $priceOverview['initial']  ?? 0;
                $discount      = $priceOverview['discount_percent'] ?? 0;
                $image         = $data['header_image'] ?? null;
                $name          = $data['name'] ?? "Game #{$appid}";

                Game::updateOrCreate(
                    ['appid' => (int) $appid],
                    [
                        'name'             => $name,
                        'last_updated_at'  => now(),
                        'price'            => $price,
                        'base_price'       => $basePrice > 0 ? $basePrice : $price,
                        'discount_percent' => $discount,
                        'image'            => $image,
                        'is_free'          => $data['is_free'] ?? false,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('WishlistController::toggle — no se pudo guardar precio base', [
                'appid' => $appid,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['status' => 'added']);
    }
}
