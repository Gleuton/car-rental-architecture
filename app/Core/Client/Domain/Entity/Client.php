<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Entity;

readonly class Client
{
    private function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function restore(int $id, string $name): self
    {
        return new self(
            id: $id,
            name: $name,
        );
    }
}
