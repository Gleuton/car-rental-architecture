<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs\CarModel;

readonly class CarModelUuidDTO
{
    private function __construct(
        public string $uuid
    ) {}

    public static function fromUuid(string $uuid): self
    {
        return new self($uuid);
    }
}
