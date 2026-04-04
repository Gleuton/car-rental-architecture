<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\ValueObjects;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;

readonly class LicensePlate
{
    /**
     * @throws CarDomainException
     */
    public function __construct(public string $value)
    {
        $this->validate();
    }

    /**
     * @throws CarDomainException
     */
    private function validate(): void
    {
        $plate = trim($this->value);

        if ($plate === '') {
            throw new CarDomainException(CarError::INVALID_LICENSE_PLATE);
        }

        if (mb_strlen($plate) < 7) {
            throw new CarDomainException(CarError::LICENSE_PLATE_TOO_SHORT);
        }

        if (mb_strlen($plate) > 10) {
            throw new CarDomainException(CarError::LICENSE_PLATE_TOO_LONG);
        }
    }
}
