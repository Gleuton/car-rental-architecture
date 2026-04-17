<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $client): void {
            if (empty($client->uuid)) {
                $client->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function newFactory()
    {
        return ClientFactory::new();
    }
}
