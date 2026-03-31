<?php

namespace App\Http\Controllers;

class HomeController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.home', [
            'featuredGame' => null,
            'trendingGames' => [],
            'newReleases' => [],
            'topPlayed' => [],
        ]);
    }
}
