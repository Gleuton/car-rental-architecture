<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Entity;

class CarModel
{
    private function __construct(
        public ?int $id,
        public int $brandId,
        public string $name,
        public string $image,
        public int $doorsNumber,
        public int $seatsNumber,
        public bool $airbags,
        public bool $abs,
    ) {}

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
}
