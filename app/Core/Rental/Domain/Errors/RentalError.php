<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Errors;

enum RentalError: int
{
    case INVALID_DATE_FORMAT = 7001;
    case INVALID_DATE_INTERVAL = 7002;

    public function message(): string
    {
        return match ($this) {
            self::INVALID_DATE_FORMAT => 'Date must be in format Y-m-d H:i:s',
            self::INVALID_DATE_INTERVAL => 'Start date must be before end date',
        };
    }
}
