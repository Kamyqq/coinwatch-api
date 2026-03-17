<?php

namespace App\Services;

use App\Models\Cryptocurrency;
use \Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinGeckoService
{
    private array $coins = [
        'bitcoin' => 'BTC',
        'ethereum' => 'ETH',
        'solana' => 'SOL',
        'dogecoin' => 'DOGE',
    ];

    public function fetchAndSavePrices(): void
    {
        $ids = implode(',', array_keys($this->coins));

        $response = Http::get("https://api.coingecko.com/api/v3/simple/price?ids={$ids}&vs_currencies=pln");

        if ($response->failed()) {
            Log::error('CoinGecko API connection failed');
            return;
        }

        $data = $response->json();

        foreach ($data as $coinId => $priceData) {
            Cryptocurrency::updateOrCreate(
                ['name' => ucfirst($coinId)],
                [
                    'symbol' => $this->coins[$coinId] ?? strtoupper($coinId),
                    'price' => $priceData['pln'],
                ]
            );
        }

        Log::info('Crypto prices successfully updated from CoinGecko');
    }
}
