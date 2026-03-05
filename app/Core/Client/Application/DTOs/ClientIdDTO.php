<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

readonly class ClientIdDTO
{
    private function __construct(
        public int $id,
    ) {}

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
