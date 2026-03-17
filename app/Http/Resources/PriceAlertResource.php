<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'crypto_name' => $this->cryptocurrency->name,
            'crypto_symbol' => $this->symbol,
            'target_price' => (float) $this->target_price,
            'direction' => $this->direction,
            'is_triggered' => (bool) $this->is_triggered,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
