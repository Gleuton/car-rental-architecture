<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Brand extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'image',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $brand): void {
            if (empty($brand->uuid)) {
                $brand->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_uuid', 'uuid');
    }
}
