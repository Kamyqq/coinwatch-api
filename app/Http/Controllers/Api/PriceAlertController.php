<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceAlertRequest;
use App\Http\Resources\PriceAlertResource;
use App\Models\PriceAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $alerts = $request->user()->priceAlerts()->with('cryptocurrency')->paginate(10);

        return PriceAlertResource::collection($alerts);
    }

    public function store(StorePriceAlertRequest $request)
    {
        $alert = $request->user()->priceAlerts()->create(
            $request->validated() + ['is_triggered' => false]);

        return new PriceAlertResource($alert);
    }

    public function destroy(Request $request, PriceAlert $priceAlert)
    {
        $this->authorize('delete', $priceAlert);
        $priceAlert->delete();

        return response()->json(['message' => 'Alert deleted successfully.'], 200);
    }
}
