<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;

class Car
{
    /**
     * @throws CarDomainException
     */
    private function __construct(
        public readonly ?int $id,
        public readonly int $carModelId,
        public readonly string $licensePlate,
        public readonly string $color,
        public readonly bool $isAvailable,
        public readonly int $km
    ) {
        $this->validateLicensePlate();
        $this->validateColor();
        $this->validateKm();
    }

    /**
     * @throws CarDomainException
     */
    public static function new(int $carModelId, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(null, $carModelId, $licensePlate, $color, $isAvailable, $km);
    }

    /**
     * @throws CarDomainException
     */
    public static function restore(
        int $id,
        int $car_model_id,
        string $license_plate,
        string $color,
        bool $is_available,
        int $km
    ): self {
        return new self($id, $car_model_id, $license_plate, $color, $is_available, $km);
    }

    /**
     * @throws CarDomainException
     */
    public function update(
        ?int $carModelId = null,
        ?string $licensePlate = null,
        ?string $color = null,
        ?bool $isAvailable = null,
        ?int $km = null
    ): self {
        $newCarModelId = $carModelId ?? $this->carModelId;
        $newLicensePlate = $licensePlate ?? $this->licensePlate;
        $newColor = $color ?? $this->color;
        $newIsAvailable = $isAvailable ?? $this->isAvailable;
        $newKm = $km ?? $this->km;

        return new self(
            $this->id,
            $newCarModelId,
            $newLicensePlate,
            $newColor,
            $newIsAvailable,
            $newKm
        );
    }

    /**
     * @throws CarDomainException
     */
    private function validateLicensePlate(): void
    {
        $plate = trim($this->licensePlate);

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

    /**
     * @throws CarDomainException
     */
    private function validateColor(): void
    {
        $color = trim($this->color);

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

    /**
     * @throws CarDomainException
     */
    private function validateKm(): void
    {
        if ($this->km < 0) {
            throw new CarDomainException(CarError::INVALID_KM);
        }
    }
}
