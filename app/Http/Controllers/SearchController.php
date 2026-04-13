<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Support\Facades\Config;

require_once app_path().'/Includes/steam_wrapper.php';

class SearchController
{
    const MAX_RESULTS = 20000; // Max games to request per page

    const MAX_PAGES = 7; // Max pages to look through when searching game

    const LAST_RESULTS_CACHE = 'lastresults';

    public function search($query)
    {

        $clean_query = trim(strtolower($query));
        $queryArray = explode(' ', $clean_query);

        $games = getSearch($clean_query);

        // Problem: If there is only one match from our db, only that game will be returned
        // This shouldn't happen since we're storing more than one game every search,
        // but it's still something to consider
        // if(count($games) === 0) {
        //     $games = self::searchOnSteam($queryArray);
        // }

        $games = array_slice($games, 0, 10);

        // Fetch more details
        for($i = 0; $i < count($games); $i++) {
            $appid = $games[$i]['appid'];
            $games[$i] = getAppDetails($appid, "es")[$appid];
        }
        

        return $games;
    }

    private function searchOnDatabase($queryArray) {
        $searchQuery = Game::query();
        foreach($queryArray as $q) {
            $searchQuery->where('name', 'LIKE', '%'.$q.'%');
        }
        return $searchQuery->get()->toArray();
    }

    private function searchOnSteam($queryArray) {
        $api_key = config('app.api_key');

        $found = [];
        $last_appid = 0;


        for ($i = 0; $i < self::MAX_PAGES; $i++) {
            $applist = getAppList($api_key, self::MAX_RESULTS, $last_appid);

            if (!$applist) {
                return [];
            }

            $last_appid = $applist['last_appid'];

            foreach ($applist['apps'] as $app) {
                $clean_name = trim(strtolower($app['name']));
                $appid = $app['appid'];
                
                if (
                    array_all($queryArray, fn ($q) => str_contains($clean_name, $q)) ||
                    $queryArray[0] === strval($appid)
                    ) {
                    // Normalize date
                    $app['last_modified'] = date('Y-m-d H:i:s', 1774958057);
                    $found[] = $app;
                }

                if (count($found) >= 10) {
                    break 2;
                }
            }

            if (! $applist['have_more_results']) {
                break;
            }
        }

        return $found;
    }

    public function index()
    {
        $query = $_GET['query'] ?? '';

        return response()->json([
            'results' => $this->search($query),
        ]);
    }
}
