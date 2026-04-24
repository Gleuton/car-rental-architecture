<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Errors;

enum CarModelError: int
{
    case ALREADY_EXISTS = 5001;

    case NUMBER_OF_SETS = 5002;
    case DOORS_NUMBER = 5003;

    public function message(): string
    {
        return match ($this) {
            self::ALREADY_EXISTS => 'Car model already exists for this brand',
            self::NUMBER_OF_SETS => 'Seats number must be between 2 and 7',
            self::DOORS_NUMBER => 'Doors number must be between 2 and 5',
        };
    }
}
