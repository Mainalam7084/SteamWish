<?php

use App\Console\Commands\CheckPrices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Revisar precios de juegos en wishlists cada 6 horas
Schedule::command('prices:check')->everySixHours()->withoutOverlapping();
