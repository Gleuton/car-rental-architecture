<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Errors;

enum CarError: int
{
    case INVALID_LICENSE_PLATE = 6001;
    case LICENSE_PLATE_TOO_SHORT = 6002;
    case LICENSE_PLATE_TOO_LONG = 6003;
    case INVALID_COLOR = 6004;
    case COLOR_TOO_SHORT = 6005;
    case COLOR_TOO_LONG = 6006;
    case INVALID_KM = 6007;
    case NOT_FOUND = 6008;
    case ALREADY_EXISTS = 6009;
    case MODEL_NOT_FOUND = 6010;

    public function message(): string
    {
        return match ($this) {
            self::INVALID_LICENSE_PLATE => 'License plate cannot be empty',
            self::LICENSE_PLATE_TOO_SHORT => 'License plate must have at least 7 characters',
            self::LICENSE_PLATE_TOO_LONG => 'License plate cannot exceed 10 characters',
            self::INVALID_COLOR => 'Color cannot be empty',
            self::COLOR_TOO_SHORT => 'Color must have at least 3 characters',
            self::COLOR_TOO_LONG => 'Color cannot exceed 50 characters',
            self::INVALID_KM => 'Kilometers must be zero or positive',
            self::NOT_FOUND => 'Car not found',
            self::ALREADY_EXISTS => 'Car with this license plate already exists',
            self::MODEL_NOT_FOUND => 'Car model not found',
        };
    }
}
