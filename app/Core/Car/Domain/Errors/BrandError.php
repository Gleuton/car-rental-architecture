<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Errors;

enum BrandError: int
{
    case ALREADY_EXISTS = 4001;
    case INVALID_NAME = 4002;
    case NAME_TOO_SHORT = 4003;
    case NAME_TOO_LONG = 4004;
    case NOT_FOUND = 4005;
    case LOGO_PATH_TOO_LONG = 4006;
    case LOGO_PATH_EMPTY = 4007;

    public function message(): string
    {
        return match ($this) {
            self::ALREADY_EXISTS => 'Brand already exists',
            self::INVALID_NAME => 'Brand name cannot be empty',
            self::NAME_TOO_SHORT => 'Brand name must have at least 3 characters',
            self::NAME_TOO_LONG => 'Brand name too long',
            self::NOT_FOUND => 'Brand not found',
            self::LOGO_PATH_TOO_LONG => 'Brand logo path cannot exceed 255 characters.',
            self::LOGO_PATH_EMPTY => 'Brand logo path cannot be empty.',
        };
    }
}
