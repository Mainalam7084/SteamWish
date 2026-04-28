<?php

namespace App\Console\Commands;

use App\Jobs\CheckWishlistPrices;
use Illuminate\Console\Command;

class CheckPrices extends Command
{
    protected $signature   = 'prices:check';
    protected $description = 'Comprueba los precios de todos los juegos en wishlists y notifica bajadas de precio.';

    public function handle(): int
    {
        $this->info('Despachando job de revisión de precios...');
        CheckWishlistPrices::dispatch();
        $this->info('Job despachado correctamente.');
        return Command::SUCCESS;
    }
}
