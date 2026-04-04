<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\LicensePlate;

class Car
{
    /**
     * @throws CarDomainException
     */
    private function __construct(
        public ?int $id,
        public int $carModelId,
        private LicensePlate $licensePlate,
        public string $color,
        public bool $isAvailable,
        public int $km
    ) {
        $this->validateColor($this->color);
        $this->validateKm($this->km);
    }

    /**
     * @throws CarDomainException
     */
    public static function new(int $carModelId, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(null, $carModelId, new LicensePlate($licensePlate), $color, $isAvailable, $km);
    }

    /**
     * @throws CarDomainException
     */
    public static function restore(
        int $id,
        int $carModelId,
        string $licensePlate,
        string $color,
        bool $isAvailable,
        int $km
    ): self {
        return new self($id, $carModelId, new LicensePlate($licensePlate), $color, $isAvailable, $km);
    }

    /**
     * @throws CarDomainException
     */
    public function changeLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = new LicensePlate($licensePlate);

        return $this;
    }

    /**
     * @throws CarDomainException
     */
    public function changeColor(string $color): self
    {
        $this->validateColor($color);
        $this->color = $color;

        return $this;
    }

    public function markAsAvailable(): self
    {
        $this->isAvailable = true;

        return $this;
    }

    public function markAsUnavailable(): self
    {
        $this->isAvailable = false;

        return $this;
    }

    public function licensePlate(): string
    {
        return $this->licensePlate->value;
    }

    /**
     * @throws CarDomainException
     */
    private function validateColor(string $color): void
    {
        $color = trim($color);

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
    private function validateKm(int $km): void
    {
        if ($km < 0) {
            throw new CarDomainException(CarError::INVALID_KM);
        }
    }
}
