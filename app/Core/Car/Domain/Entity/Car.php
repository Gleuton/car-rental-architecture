<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\Color;
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
        public Color $color,
        public bool $isAvailable,
        public int $km
    ) {
        $this->validateKm($this->km);
    }

    /**
     * @throws CarDomainException
     */
    public static function new(int $carModelId, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(null, $carModelId, new LicensePlate($licensePlate), new Color($color), $isAvailable, $km);
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
        return new self($id, $carModelId, new LicensePlate($licensePlate), new Color($color), $isAvailable, $km);
    }

    /**
     * @throws CarDomainException
     */
    public function changeLicensePlate(?string $licensePlate): self
    {
        $this->licensePlate = $licensePlate ? new LicensePlate($licensePlate) : $this->licensePlate;

        return $this;
    }

    /**
     * @throws CarDomainException
     */
    public function changeColor(?string $color): self
    {
        $this->color = $color ? new Color($color) : $this->color;

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

    public function color(): string
    {
        return $this->color->value;
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
