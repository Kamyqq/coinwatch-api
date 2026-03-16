<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceAlert extends Model
{
    protected $fillable = ['user_id', 'cryptocurrency_id', 'target_price', 'direction', 'is_triggered'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cryptocurrency::class);
    }
}
