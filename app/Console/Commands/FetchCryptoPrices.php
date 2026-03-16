<?php

namespace App\Console\Commands;

use App\Services\CoinGeckoService;
use Illuminate\Console\Command;

class FetchCryptoPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:fetch-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches latest cryptocurrency prices from CoinGecko API and updates DB';

    /**
     * Execute the console command.
     */
    public function handle(CoinGeckoService $coingeckoService): void
    {
        $this->info('Fetching crypto prices...');
        $coingeckoService->fetchAndSavePrices();
        $this->info('Prices update successfully!');
    }
}
