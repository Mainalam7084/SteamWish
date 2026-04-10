<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->pluck('appid')->toArray();
        $games = [];

        return view('pages.wishlist', compact('wishlists', 'games'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['appid' => 'required|string']);
        $user = Auth::user();
        $appid = $request->input('appid');

        $wishlist = Wishlist::where('user_id', $user->id)->where('appid', $appid)->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json(['status' => 'removed']);
        } else {
            Wishlist::create(['user_id' => $user->id, 'appid' => $appid]);

            return response()->json(['status' => 'added']);
        }
    }
}
