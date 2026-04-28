<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

require_once app_path() . '/Includes/steam_wrapper.php';
require_once app_path() . '/Includes/isthereanydeal_wrapper.php';

class GameController
{
    private GameService $gameService;
    private SearchController $searchController;

    public function __construct(GameService $gameService, SearchController $searchController)
    {
        $this->gameService = $gameService;
        $this->searchController = $searchController;
    }

    /**
     * Muestra los resultados de búsqueda.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = $request->query('q');
        $results = $this->searchController->search($query);

        return view('pages.search', compact('results', 'query'));
    }

    /**
     * Muestra la página de detalles de un juego.
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $itad_api_key = config('app.itad_api_key');

        $appid = $request->query('appid');
        if ($appid === null) {
            abort(404, 'No se encontró el identificador del juego.');
        }

        $apiResponse = $this->gameService->GetDetails($appid);

        // Validar que Steam nos haya devuelto datos válidos para este appid
        if (!isset($apiResponse['success']) || !$apiResponse['success'] || !isset($apiResponse['data'])) {
            abort(404, 'El juego no está disponible o no tiene datos públicos en Steam.');
        }

        $details = $apiResponse['data'];

        $app_name = $details['name'] ?? 'Unknown Game';
        $app_short_desc = $details['short_description'] ?? '';
        $app_header_img = $details['header_image'] ?? 'https://placehold.co/460x215/0F3A52/FACC15?text=No+Image';
        $app_price = $details['price_overview']['final_formatted'] ?? 'Gratis';
        $app_price_numeric = ($details['price_overview']['final'] ?? 0) / 100;
        $app_publisher = $details['publishers'][0] ?? 'Unknown Publisher';
        $app_developer = $details['developers'][0] ?? 'Unknown Developer';
        $app_detailed_desc = $details['detailed_description'] ?? '';

        $discount_percent = $details['price_overview']['discount_percent'] ?? 0;
        $discount_formatted = $discount_percent > 0 ? "(-$discount_percent%)" : '';

        $screenshots = $details["screenshots"] ?? [];

        // TODO: Conseguir más de solo 3 meses de historial.
        $price_history = getPriceHistory($itad_api_key, $appid, new DateTime("first day of this month -3 months"), "es");

        // Ordenar historial de precios por fecha ascendente.
        usort($price_history, function ($a, $b) {
            return $a["timestamp"] <=> $b["timestamp"];
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

}
