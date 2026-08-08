<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\Color;
use App\Core\Car\Domain\ValueObjects\LicensePlate;
use App\Core\Car\Domain\ValueObjects\Mileage;
use Illuminate\Support\Str;

class Car
{
    private function __construct(
        public readonly string $uuid,
        public readonly string $carModelUuid,
        public readonly ?string $carModelName,
        public readonly ?string $brandName,
        private LicensePlate $licensePlate,
        private Color $color,
        private bool $available,
        private readonly Mileage $km
    ) {}

    /**
     * @throws CarDomainException
     */
    public static function new(string $carModelUuid, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(
            (string) Str::uuid(),
            $carModelUuid,
            null,
            null,
            new LicensePlate($licensePlate),
            new Color($color),
            $isAvailable,
            new Mileage($km)
        );
    }

    /**
     * @throws CarDomainException
     */
    public static function restore(
        string $carModelUuid,
        string $licensePlate,
        string $color,
        bool $isAvailable,
        int $km,
        ?string $uuid = null,
        ?string $carModelName = null,
        ?string $brandName = null,
    ): self {
        return new self(
            $uuid ?? (string) Str::uuid(),
            $carModelUuid,
            $carModelName,
            $brandName,
            new LicensePlate($licensePlate),
            new Color($color),
            $isAvailable,
            new Mileage($km)
        );
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
        $this->available = true;

        return $this;
    }

    public function markAsUnavailable(): self
    {
        $this->available = false;

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

    public function km(): int
    {
        return $this->km->mileage;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function carModelName(): ?string
    {
        return $this->carModelName;
    }

    public function brandName(): ?string
    {
        return $this->brandName;
    }
}
