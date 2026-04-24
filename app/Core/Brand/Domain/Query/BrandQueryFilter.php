<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Query;

readonly class BrandQueryFilter
{
    private function __construct(
        public ?string $search,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page
    ) {}

    public static function create(?string $search, string $orderBy, string $direction, int $perPage, int $page): self
    {
        return new self($search, $orderBy, $direction, $perPage, $page);
    }
}
