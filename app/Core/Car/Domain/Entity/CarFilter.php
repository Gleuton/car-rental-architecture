<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

readonly class CarFilter
{
    private function __construct(
        public ?string $licensePlate,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page
    ) {}

    public static function create(?string $licensePlate, string $orderBy, string $direction, int $perPage, int $page): self
    {
        return new self($licensePlate, $orderBy, $direction, $perPage, $page);
    }
}
