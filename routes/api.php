<?php

use App\Http\Controllers\Api\PriceAlertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/alerts', [PriceAlertController::class, 'index']);
    Route::post('/alerts', [PriceAlertController::class, 'store']);
    Route::delete('/alerts/{priceAlert}', [PriceAlertController::class, 'destroy']);
});
