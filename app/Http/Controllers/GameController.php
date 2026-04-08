<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require_once app_path() . '/Includes/steam_wrapper.php';
require_once app_path() . '/Includes/isthereanydeal_wrapper.php';

class GameController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $_GET['q'];
        $controller = new SearchController;
        $results = $controller->search($query);

        return view('pages.search', compact('results', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $appid = $_GET['appid'] ?? null;
        if ($appid == null) {
            die('No appid'); // Mejor redirigir a página de error
        }

        $details = getAppDetails($appid, 'es')[$appid]['data'];
        $app_name = $details['name'];
        $app_short_desc = $details['short_description'];
        $app_header_img = $details['header_image'];
        $app_price = $details['price_overview']['final_formatted'] ?? 'Free';
        $app_publisher = $details['publishers'][0] ?? 'Unknown';
        $app_developer = $details['developers'][0] ?? 'Unknown';
        $app_detailed_desc = $details['detailed_description'] ?? '';

        $discount_percent = $details['price_overview']['discount_percent'] ?? 0;
        $discount_formatted = $discount_percent > 0 ? "(-$discount_percent%)" : '';

        $screenshots = $details["screenshots"];

        return view('pages.game', compact('app_name', 'app_short_desc', 'app_header_img', 
        'app_price', 'app_publisher', 'app_developer', 'app_detailed_desc',
        'discount_percent','discount_formatted', 'screenshots'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
