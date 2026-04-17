<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

readonly class ClientIdDTO
{
    private function __construct(
        public string $uuid,
    ) {}

    public static function fromUuid(string $uuid): self
    {
        return new self($uuid);
    }
}
