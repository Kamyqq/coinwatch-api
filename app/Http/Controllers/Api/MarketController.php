<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MarketController extends Controller
{
    public function index()
    {
        $isCached = Cache::has('market_prices');

        $prices = Cache::remember('market_prices', 60, function () {
            return Cryptocurrency::select('id', 'name', 'symbol', 'price', 'updated_at')
                ->get()
                ->toArray();
        });

        return response()->json([
            'data' => $prices,
            'source' => $isCached ? 'cache' : 'database'
        ]);
    }
}
