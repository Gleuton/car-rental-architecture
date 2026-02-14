<?php

namespace App\Core\Brand\Domain\Entity;

readonly class Brand
{
    private function __construct(
        public ?int $id,
        public string $name,
        public string $image
    ) {
    }

    public static function create(string $name, string $image): self
    {
        return new self(null, $name, $image);
    }

    public static function createWithId(int $id, string $name, string $image): self
    {
        return new self($id, $name, $image);
    }
}
