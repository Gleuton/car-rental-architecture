<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\ValueObjects;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;

readonly class Color
{
    /**
     * @throws CarDomainException
     */
    public function __construct(
        public string $value
    ) {
        $this->validate();
    }

    /**
     * @throws CarDomainException
     */
    private function validate(): void
    {
        $color = trim($this->value);

        if ($color === '') {
            throw new CarDomainException(CarError::INVALID_COLOR);
        }

        if (mb_strlen($color) < 3) {
            throw new CarDomainException(CarError::COLOR_TOO_SHORT);
        }

        if (mb_strlen($color) > 50) {
            throw new CarDomainException(CarError::COLOR_TOO_LONG);
        }
    }
}
