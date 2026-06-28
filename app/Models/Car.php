<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property string $car_model_uuid
 * @property string $license_plate
 * @property string $color
 * @property-read CarModel|null $carModel
 * @property bool $is_available
 * @property int $km
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Car extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['uuid', 'car_model_uuid', 'license_plate', 'color', 'is_available', 'km'];

    protected static function booted(): void
    {
        static::creating(static function (self $car): void {
            if (empty($car->uuid)) {
                $car->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /** @return BelongsTo<CarModel, Car> */
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
