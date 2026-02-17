<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = ['brand_id', 'name', 'image', 'doors', 'seats', 'airbags', 'abs'];
}
