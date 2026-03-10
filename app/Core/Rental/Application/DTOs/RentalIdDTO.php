<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

readonly class RentalIdDTO
{
    private function __construct(
        public int $id,
    ) {}

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
