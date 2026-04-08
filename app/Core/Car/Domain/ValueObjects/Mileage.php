<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\ValueObjects;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;

readonly class Mileage
{
    /**
     * @throws CarDomainException
     */
    public function __construct(public int $mileage)
    {
        $this->validate();
    }

    /**
     * @throws CarDomainException
     */
    private function validate(): void
    {
        if ($this->mileage < 0) {
            throw new CarDomainException(CarError::INVALID_KM);
        }
    }
}
