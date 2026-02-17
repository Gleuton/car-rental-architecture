<?php

namespace App\Core\Brand\Application\DTOs;

class BrandIdDTO
{
    private function __construct(
        public int $id
    ) {
    }

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
