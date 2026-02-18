<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Entity;

use App\Core\CarModel\Domain\Errors\CarModelError;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;

class CarModel
{
    /**
     * @throws CarModelDomainException
     */
    private function __construct(
        public ?int $id,
        public int $brandId,
        public string $name,
        public string $image,
        public int $doorsNumber,
        public int $seatsNumber,
        public bool $airbags,
        public bool $abs,
    ) {
        $this->validateSeatsNumber();
        $this->validateDoorsNumber();
    }

    /**
     * @throws CarModelDomainException
     */
    public static function new(
        int $brandId,
        string $name,
        string $image,
        int $doorsNumber,
        int $seatsNumber,
        bool $airbags,
        bool $abs
    ): self {
        return new self(null, $brandId, $name, $image, $doorsNumber, $seatsNumber, $airbags, $abs);
    }

    /**
     * @throws CarModelDomainException
     */
    public static function restore(
        int $id,
        int $brandId,
        string $name,
        string $image,
        int $doorsNumber,
        int $seatsNumber,
        bool $airbags,
        bool $abs
    ): self {
        return new self($id, $brandId, $name, $image, $doorsNumber, $seatsNumber, $airbags, $abs);
    }

    /**
     * @throws CarModelDomainException
     */
    private function validateSeatsNumber(): void
    {
        if ($this->seatsNumber < 2 || $this->seatsNumber > 7) {
            throw new CarModelDomainException(CarModelError::NUMBER_OF_SETS);
        }
    }

    /**
     * @throws CarModelDomainException
     */
    private function validateDoorsNumber(): void
    {
        if ($this->doorsNumber < 2 || $this->doorsNumber > 5) {
            throw new CarModelDomainException(CarModelError::DOORS_NUMBER);
        }
    }
}
