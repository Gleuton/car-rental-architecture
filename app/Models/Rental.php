<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'car_id',
        'car_uuid',
        'client_id',
        'client_uuid',
        'start_date',
        'end_date',
        'day_price_cents',
        'initial_km',
        'final_km',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $rental): void {
            if (empty($rental->uuid)) {
                $rental->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected array $dates = ['start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_uuid', 'uuid');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_uuid', 'uuid');
    }
}
