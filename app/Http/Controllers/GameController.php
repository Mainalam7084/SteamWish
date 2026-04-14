<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $itad_api_key = config('app.itad_api_key');

        $appid = $_GET['appid'] ?? null;
        if ($appid == null) {
            die('No appid'); // Mejor redirigir a página de error
        }

        $details = getAppDetails($appid, 'es')[$appid]['data'];
        $app_name = $details['name'];
        $app_short_desc = $details['short_description'];
        $app_header_img = $details['header_image'];
        $app_price = $details['price_overview']['final_formatted'] ?? 'Free';
        $app_price_numeric = ($details['price_overview']['final'] ?? 0) / 100;
        $app_publisher = $details['publishers'][0] ?? 'Unknown';
        $app_developer = $details['developers'][0] ?? 'Unknown';
        $app_detailed_desc = $details['detailed_description'] ?? '';

        $discount_percent = $details['price_overview']['discount_percent'] ?? 0;
        $discount_formatted = $discount_percent > 0 ? "(-$discount_percent%)" : '';

        $screenshots = $details["screenshots"];


        // TODO: Conseguir mas de solo 3 meses de historial

        $price_history = getPriceHistory($itad_api_key, $appid, "es");

        // Hay que ordenar por fecha
        usort($price_history, function($a, $b) {
            return $a["timestamp"] > $b["timestamp"];
        });

        $price_history_timestamps = [];
        $price_history_prices = [];

        $last_price = null;
        foreach($price_history as $entry) {
            $timestamp = (new DateTime($entry["timestamp"]))->format("d/m/Y");
            $price = $entry["deal"]["price"]["amount"];

            // Saltarse precios repetidos, solo interesa el cambio
            if($last_price != null && $last_price == $price){
                continue;
            }

            $price_history_timestamps[] = $timestamp;
            $price_history_prices[] = $price;

            $last_price = $price;
        }

        // Añadimos el precio de la fecha actual
        $price_history_timestamps[] = (new DateTime())->format("d/m/Y");
        $price_history_prices[] = $app_price_numeric;

        // Wishlist state for the logged-in user
        $inWishlist = false;
        if (Auth::check()) {
            $inWishlist = Auth::user()
                ->wishlists()
                ->where('appid', (string) $appid)
                ->exists();
        }

        return view('pages.game', compact(
            'appid',
            'app_name', 'app_short_desc', 'app_header_img',
            'app_price', 'app_publisher', 'app_developer', 'app_detailed_desc',
            'discount_percent', 'discount_formatted', 'screenshots',
            'price_history_timestamps', 'price_history_prices',
            'inWishlist'
        ));
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
