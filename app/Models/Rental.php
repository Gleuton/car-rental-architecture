<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    protected $fillable = [
        'car_id',
        'client_id',
        'start_date',
        'end_date',
        'day_price_cents',
        'initial_km',
        'final_km',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
}
