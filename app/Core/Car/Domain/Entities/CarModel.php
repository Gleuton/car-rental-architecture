<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entities;

use App\Core\Car\Domain\Errors\CarModelError;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use Illuminate\Support\Str;

class CarModel
{
    /**
     * @throws CarModelDomainException
     */
    private function __construct(
        public readonly string $uuid,
        public readonly string $brandUuid,
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
        string $brandUuid,
        string $name,
        string $image,
        int $doorsNumber,
        int $seatsNumber,
        bool $airbags,
        bool $abs
    ): self {
        return new self((string) Str::uuid(), $brandUuid, $name, $image, $doorsNumber, $seatsNumber, $airbags, $abs);
    }

    /**
     * @throws CarModelDomainException
     */
    public static function restore(
        string $brandUuid,
        string $name,
        string $image,
        int $doorsNumber,
        int $seatsNumber,
        bool $airbags,
        bool $abs,
        ?string $uuid = null,
    ): self {
        return new self($uuid ?? (string) Str::uuid(), $brandUuid, $name, $image, $doorsNumber, $seatsNumber, $airbags, $abs);
    }

    /**
     * @throws CarModelDomainException
     */
    public function update(
        ?string $brandUuid,
        ?string $name,
        string $image,
        ?int $doorsNumber,
        ?int $seatsNumber,
        ?bool $airbags,
        ?bool $abs
    ): self {
        $newBrandUuid = $brandUuid ?? $this->brandUuid;
        $newName = $name ?? $this->name;
        $newDoorsNumber = $doorsNumber ?? $this->doorsNumber;
        $newSeatsNumber = $seatsNumber ?? $this->seatsNumber;
        $newAirbags = $airbags ?? $this->airbags;
        $newAbs = $abs ?? $this->abs;

        return new self(
            $this->uuid,
            $newBrandUuid,
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
