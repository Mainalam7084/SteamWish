<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

require_once app_path() . '/Includes/steam_wrapper.php';

class WishlistController
{
    // ==========================================
    // GET /api/wishlist-ids  — for JS use
    // ==========================================

    public function ids()
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        return response()->json(
            Auth::user()->wishlists()->pluck('appid')->map(fn ($id) => (int) $id)->values()
        );
    }

    // ==========================================
    // GET /api/wishlist-preview  — for JS navbar
    // ==========================================

    public function preview()
    {
        if (!Auth::check()) {
            return response()->json([]);
        }

        $appids = Auth::user()
            ->wishlists()
            ->latest()
            ->take(3)
            ->pluck('appid')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        // 1. Pull from DB
        $dbGames = Game::whereIn('appid', $appids)
            ->get()
            ->keyBy('appid');

        $games = [];
        foreach ($appids as $appid) {
            if (isset($dbGames[$appid]) && $dbGames[$appid]->name) {
                $g = $dbGames[$appid];
                $games[] = [
                    'appid' => $g->appid,
                    'name'  => $g->name,
                    'image' => $g->image ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                ];
            } else {
                $steamData = getAppDetails($appid, "es")[$appid]['data'] ?? null;
                $games[] = [
                    'appid' => $appid,
                    'name'  => $steamData['name'] ?? "Game #{$appid}",
                    'image' => $steamData['header_image'] ?? "https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/{$appid}/header.jpg",
                ];
            }
        }

        return response()->json($games);
    }

    // ==========================================
    // GET /wishlist  — show saved games
    // ==========================================

    public function index()
    {
        $user    = Auth::user();
        $appids  = $user->wishlists()->pluck('appid')->map(fn ($id) => (int) $id)->toArray();

        if (empty($appids)) {
            return view('pages.wishlist', ['games' => []]);
        }

        // 1. Pull what we already have in the local DB
        $dbGames = Game::whereIn('appid', $appids)
            ->get()
            ->keyBy('appid');

        $games = [];

        foreach ($appids as $appid) {
            if (isset($dbGames[$appid]) && $dbGames[$appid]->name) {
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
                // 2. Fetch using Steam Wrapper
                $steamData = getAppDetails($appid, "es");
                $data = $steamData[$appid]['data'] ?? null;

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

    // ==========================================
    // POST /wishlist/toggle  — add or remove
    // ==========================================

    public function toggle(Request $request)
    {
        $request->validate(['appid' => 'required|string']);
        $user  = Auth::user();
        $appid = $request->input('appid');

        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('appid', $appid)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create(['user_id' => $user->id, 'appid' => $appid]);
        return response()->json(['status' => 'added']);
    }
}
