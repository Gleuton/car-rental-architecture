<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\ValueObjects;

use Illuminate\Support\Str;

readonly class BrandUuid
{
    public string $value;

    public function __construct(?string $uuid = null)
    {
        $this->value = $uuid ?? (string) Str::uuid();
    }
}
