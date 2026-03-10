<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Entity;

readonly class RentalFilter
{
    private function __construct(
        public ?string $startDateFrom,
        public ?string $startDateTo,
        public ?string $endDateFrom,
        public ?string $endDateTo,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function create(
        ?string $startDateFrom,
        ?string $startDateTo,
        ?string $endDateFrom,
        ?string $endDateTo,
        string $orderBy,
        string $direction,
        int $perPage,
        int $page,
    ): self {
        return new self(
            $startDateFrom,
            $startDateTo,
            $endDateFrom,
            $endDateTo,
            $orderBy,
            $direction,
            $perPage,
            $page,
        );
    }
}
