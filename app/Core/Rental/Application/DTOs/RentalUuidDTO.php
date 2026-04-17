<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

readonly class RentalUuidDTO
{
    private function __construct(
        public string $uuid,
    ) {}

    public static function fromUuid(string $uuid): self
    {
        return new self($uuid);
    }
}
