<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\DTOs;

readonly class CarModelIdDTO
{
    private function __construct(
        public string $uuid
    ) {}

    public static function fromUuid(string $uuid): self
    {
        return new self($uuid);
    }
}
