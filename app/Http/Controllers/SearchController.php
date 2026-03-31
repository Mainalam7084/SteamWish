<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

require(app_path() . '/Includes/steam_wrapper.php');

class SearchController
{
    const MAX_RESULTS = 20000; // Max games to request per page
    const MAX_PAGES = 7; // Max pages to look through when searching game
    const LAST_RESULTS_CACHE = "lastresults";

    public function index()
    {
        $api_key = Config::get("app.api_key");

        $query = $_GET['query'];

        $clean_query = trim(strtolower($query));
        $found = [];
        $details_cache = SearchController::getCache(SearchController::LAST_RESULTS_CACHE) ?? [];

        $last_appid = 0;

        // Search in only x page results to avoid excessive queries
        for ($i = 0; $i < SearchController::MAX_PAGES; $i++) {
            // See if we have the page cached, otherwise get and cache it
            // Problem: List changes regularly as games are added, our cache does not update
            $applist = SearchController::getOrCache("applist_$last_appid", fn() => 
            getAppList($api_key, SearchController::MAX_RESULTS, $last_appid));

            if (!$applist) {
                echo json_encode(["error" => "Error fetching app list"]);
                exit;
            }

            $last_appid = $applist["last_appid"];

            $apps = $applist["apps"];
            foreach ($apps as $app) {
                $clean_name = trim(strtolower($app["name"]));
                $appid = $app["appid"];

                $queries = explode(" ", $clean_query);
                if (
                    array_all($queries, fn($q) => str_contains($clean_name, $q)) ||
                    $clean_query === strval($appid)
                ) {
                    // When there's no connection but the applist is already cached, we might get null app details if not cached
                    // Ideally we'd handle some sort of error, but file_get_contents doesn't throw errors,
                    // and there might still be some cached data we can use
                    $details = array_find($details_cache, fn($d) => $d["data"]["steam_appid"] === $appid)
                        ?? getAppDetails($appid)[$appid];
                    if ($details)
                        $found[] = $details;
                }

                // 10 matches is more than enough, avoid excessive requests on pages/appdetails
                if (count($found) >= 10)
                    break 2;
            }

            if ($applist["have_more_results"] == false)
                break;
        }

        // Save the last search to the cache to avoid re-querying too many app details
        SearchController::saveCache(SearchController::LAST_RESULTS_CACHE, $found);

        return response(json_encode(["results" => $found]))->header("Content-Type", "application/json");
    }


    // TODO: Add proper cache
    function getCachePath($cache_name)
    {
        return "";
    }

    // Try to get from the cache, or cache the getFunc result
    function getOrCache($cache_name, $getFunc)
    {
        $result = SearchController::getCache($cache_name);
        if ($result == null) {
            $result = $getFunc();
            SearchController::saveCache($cache_name, $result);
        }

        return $result;
    }

    function getCache($cache_name)
    {
        $cache_file = SearchController::getCachePath($cache_name);
        $result = null;
        if (file_exists($cache_file)) {
            $result = json_decode(file_get_contents($cache_file), true);
        }

        return $result;
    }

    function saveCache($cache_name, $content)
    {
        // TEMP
    }
}
