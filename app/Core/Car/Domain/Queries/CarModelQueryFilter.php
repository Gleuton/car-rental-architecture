<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Queries;

readonly class CarModelQueryFilter
{
    private function __construct(
        public ?string $search,
        public ?string $brandUuid,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page
    ) {}

    public static function create(
        ?string $search,
        ?string $brandUuid,
        string $orderBy,
        string $direction,
        int $perPage,
        int $page
    ): self {
        return new self($search, $brandUuid, $orderBy, $direction, $perPage, $page);
    }
}
