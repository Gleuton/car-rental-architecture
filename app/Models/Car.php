<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'car_model_uuid', 'license_plate', 'color', 'is_available', 'km'];

    protected static function booted(): void
    {
        static::creating(static function (self $car): void {
            if (empty($car->uuid)) {
                $car->uuid = (string) Str::uuid();
            }
        });

        static::saving(static function (self $car): void {
            if (empty($car->car_model_uuid)) {
                return;
            }

            $car->car_model_id = CarModel::query()
                ->where('uuid', $car->car_model_uuid)
                ->firstOrFail()
                ->id;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_uuid', 'uuid');
    }

    protected $casts = [
        'is_available' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
