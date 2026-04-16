<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'car_model_id', 'car_model_uuid', 'license_plate', 'color', 'is_available', 'km'];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    protected $casts = [
        'is_available' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
