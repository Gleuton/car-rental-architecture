<?php

namespace App\Core\Brand\Domain\Errors;

enum BrandError: int
{
    case ALREADY_EXISTS = 4001;

    public function message(): string
    {
        return match ($this) {
            self::ALREADY_EXISTS => 'Brand already exists',
        };
    }
}
