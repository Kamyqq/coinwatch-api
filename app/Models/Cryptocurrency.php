<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cryptocurrency extends Model
{
    protected $fillable = ['name', 'symbol', 'current_price'];

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }
}
