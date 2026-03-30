<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

require(app_path().'/Includes/steam_wrapper.php');

class SearchController
{
    public function index()
    {
        $api_key = Config::get("app.api_key");

        return response(json_encode([
            "api_key" => $api_key
        ]))->header("Content-Type", "application/json");
    }
}
