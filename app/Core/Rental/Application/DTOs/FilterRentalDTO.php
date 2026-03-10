<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\IndexRentalRequest;

readonly class FilterRentalDTO
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

    public static function fromRequest(IndexRentalRequest $request): self
    {
        return new self(
            startDateFrom: $request->input('start_date_from'),
            startDateTo: $request->input('start_date_to'),
            endDateFrom: $request->input('end_date_from'),
            endDateTo: $request->input('end_date_to'),
            orderBy: $request->input('order_by'),
            direction: $request->input('direction'),
            perPage: (int) $request->input('per_page'),
            page: (int) ($request->input('page') ?? 1),
        );
    }
}
