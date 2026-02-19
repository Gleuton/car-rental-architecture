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
        public readonly ?int $id,
        public readonly int $brandId,
        public readonly string $name,
        public readonly string $image,
        public readonly int $doorsNumber,
        public readonly int $seatsNumber,
        public readonly bool $airbags,
        public readonly bool $abs,
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
    public function update(
        ?int $brandId,
        ?string $name,
        string $image,
        ?int $doorsNumber,
        ?int $seatsNumber,
        ?bool $airbags,
        ?bool $abs
    ): self {
        $newBrandId = $brandId ?? $this->brandId;
        $newName = $name ?? $this->name;
        $newDoorsNumber = $doorsNumber ?? $this->doorsNumber;
        $newSeatsNumber = $seatsNumber ?? $this->seatsNumber;
        $newAirbags = $airbags ?? $this->airbags;
        $newAbs = $abs ?? $this->abs;

        return new self(
            $this->id,
            $newBrandId,
            $newName,
            $image,
            $newDoorsNumber,
            $newSeatsNumber,
            $newAirbags,
            $newAbs
        );
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
