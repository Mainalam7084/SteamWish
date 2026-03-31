<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

require(app_path() . '/Includes/steam_wrapper.php');

class SearchController
{
    const MAX_RESULTS = 20000; // Max games to request per page
    const MAX_PAGES = 7; // Max pages to look through when searching game
    const LAST_RESULTS_CACHE = "lastresults";

    public function search($query)
    {
        $api_key = Config::get("app.api_key");

        $clean_query = trim(strtolower($query));
        $found = [];
        $details_cache = self::getCache(self::LAST_RESULTS_CACHE) ?? [];

        $last_appid = 0;

        for ($i = 0; $i < self::MAX_PAGES; $i++) {
            $applist = self::getOrCache("applist_$last_appid", fn() => 
                getAppList($api_key, self::MAX_RESULTS, $last_appid)
            );

            if (!$applist) return [];

            $last_appid = $applist["last_appid"];

            foreach ($applist["apps"] as $app) {
                $clean_name = trim(strtolower($app["name"]));
                $appid = $app["appid"];

                $queries = explode(" ", $clean_query);

                if (
                    array_all($queries, fn($q) => str_contains($clean_name, $q)) ||
                    $clean_query === strval($appid)
                ) {
                    $details = array_find($details_cache, fn($d) => $d["data"]["steam_appid"] === $appid)
                        ?? getAppDetails($appid)[$appid];

                    if ($details)
                        $found[] = $details;
                }

                if (count($found) >= 10)
                    break 2;
            }

            if (!$applist["have_more_results"])
                break;
        }

        self::saveCache(self::LAST_RESULTS_CACHE, $found);

        return $found;
    }

    public function index()
    {
        $query = $_GET['query'] ?? '';
        return response()->json([
            "results" => $this->search($query)
        ]);
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
