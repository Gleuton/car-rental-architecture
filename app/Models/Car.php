<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = ['model_id', 'color', 'year', 'price'];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }
}
