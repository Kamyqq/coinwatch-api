<?php

namespace App\Services;

use App\Models\Cryptocurrency;
use Illuminate\Support\Facades\Cache;
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

        try {
            $response = Http::timeout(10)->get("https://api.coingecko.com/api/v3/simple/price?ids={$ids}&vs_currencies=pln");

            if ($response->failed()) {
                Log::error('CoinGecko API connection failed');
                return;
            }

            $data = $response->json();

            if (!is_array($data)) {
                Log::error('CoinGecko API returned invalid JSON structure');
                return;
            }

            foreach ($data as $coinId => $priceData) {
                if (!isset($priceData['pln'])) {
                    continue;
                }

                Cryptocurrency::updateOrCreate(
                    ['name' => ucfirst($coinId)],
                    [
                        'symbol' => $this->coins[$coinId] ?? strtoupper($coinId),
                        'price' => $priceData['pln'],
                    ]
                );
            }

            Cache::forget('market_prices');

            Log::info('Crypto prices successfully updated from CoinGecko');
        } catch (\Exception $e) {
            Log::error('Connection to CoinGecko failed: ' . $e->getMessage());
        }
    }
}
