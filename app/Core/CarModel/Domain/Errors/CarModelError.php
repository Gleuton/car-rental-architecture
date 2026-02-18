<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Errors;

enum CarModelError: int
{
    case ALREADY_EXISTS = 5001;

    case NUMBER_OF_SETS = 5002;

    public function message(): string
    {
        return match ($this) {
            self::ALREADY_EXISTS => 'Car model already exists for this brand',
            self::NUMBER_OF_SETS => 'Seats number must be between 2 and 7',
        };
    }
}
