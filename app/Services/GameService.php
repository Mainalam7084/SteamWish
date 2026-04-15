<?php
namespace App\Services;
use Illuminate\Support\Facades\Cache;

require_once app_path() . '/Includes/steam_wrapper.php';

const CACHE_TIME_HOURS = 1;

class GameService {
    public function GetDetails($appid) {
        $details = Cache::remember('game_details:'.$appid, now()->addHours(CACHE_TIME_HOURS), function() use ($appid) {
            return getAppDetails($appid, 'es');
        });

        return $details[$appid];
    }
} 