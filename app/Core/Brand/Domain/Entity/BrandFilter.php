<?php

namespace App\Core\Brand\Domain\Entity;

readonly class BrandFilter
{
    private function __construct(
        public ?string $search,
        public string $orderBy,
        public string $direction,
        public int $perPage,
    ) {}

    public static function create(?string $search, string $orderBy, string $direction, int $perPage): self
    {
        return new self($search, $orderBy, $direction, $perPage);
    }
}
