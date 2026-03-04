<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\DTOs;

readonly class CarModelIdDTO
{
    private function __construct(
        public int $id
    ) {}

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
