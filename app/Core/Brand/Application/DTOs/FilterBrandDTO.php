<?php

namespace App\Core\Brand\Application\DTOs;

use App\Http\Requests\Brand\IndexBrandRequest;

class FilterBrandDTO
{
    private function __construct(
        public ?string $search,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(IndexBrandRequest $request): self
    {
        return new self(
            search: $request->search,
            orderBy: $request->order_by,
            direction: $request->direction,
            perPage: (int) $request->per_page,
            page: (int) ($request->page ?? 1),
        );
    }
}
