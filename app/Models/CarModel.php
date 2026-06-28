<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property Brand|null $brand
 */
class CarModel extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['uuid', 'brand_uuid', 'name', 'image', 'doors', 'seats', 'airbags', 'abs'];

    protected static function booted(): void
    {
        static::creating(static function (self $carModel): void {
            if (empty($carModel->uuid)) {
                $carModel->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_uuid', 'uuid');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_model_uuid', 'uuid');
    }
}
