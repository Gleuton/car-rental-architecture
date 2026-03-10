<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Errors;

enum RentalError: int
{
    case INVALID_DATE_FORMAT = 7001;
    case INVALID_DATE_INTERVAL = 7002;
    case INVALID_DAY_PRICE = 7003;
    case INVALID_INITIAL_KM = 7004;
    case INVALID_FINAL_KM = 7005;
    case FINAL_KM_LESS_THAN_INITIAL = 7006;

    public function message(): string
    {
        return match ($this) {
            self::INVALID_DATE_FORMAT => 'Date must be in format Y-m-d H:i:s',
            self::INVALID_DATE_INTERVAL => 'Start date must be before or equal to end date',
            self::INVALID_DAY_PRICE => 'Day price must be greater than or equal to zero',
            self::INVALID_INITIAL_KM => 'Initial km must be greater than or equal to zero',
            self::INVALID_FINAL_KM => 'Final km must be greater than or equal to zero',
            self::FINAL_KM_LESS_THAN_INITIAL => 'Final km must be greater than or equal to initial km',
        };
    }
}
