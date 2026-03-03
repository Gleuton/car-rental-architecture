<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

class Car
{
    public function __construct(
        public ?int $id,
        public int $carModelId,
        public string $licensePlate,
        public string $color,
        public bool $isAvailable,
        public int $km
    ) {}

    public static function new(int $carModelId, string $licensePlate, string $color, bool $isAvailable, int $km): self
    {
        return new self(null, $carModelId, $licensePlate, $color, $isAvailable, $km);
    }

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
}
