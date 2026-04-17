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
        public readonly ?int $id,
        public readonly string $uuid,
        public readonly int $carModelId,
        public readonly string $carModelUuid,
        private LicensePlate $licensePlate,
        private Color $color,
        private bool $available,
        private readonly Mileage $km
    ) {}

    /**
     * @throws CarDomainException
     */
    public static function new(int $carModelId, string $carModelUuid, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(null, (string) Str::uuid(), $carModelId, $carModelUuid, new LicensePlate($licensePlate), new Color($color), $isAvailable, new Mileage($km));
    }

    /**
     * @throws CarDomainException
     */
    public static function restore(
        int $id,
        int $carModelId,
        string $carModelUuid,
        string $licensePlate,
        string $color,
        bool $isAvailable,
        int $km,
        ?string $uuid = null,
    ): self {
        return new self($id, $uuid ?? (string) Str::uuid(), $carModelId, $carModelUuid, new LicensePlate($licensePlate), new Color($color), $isAvailable, new Mileage($km));
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
}
