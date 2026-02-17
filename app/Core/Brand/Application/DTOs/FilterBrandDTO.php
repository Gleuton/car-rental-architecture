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
            search: $request->input('search'),
            orderBy: $request->input('order_by'),
            direction: $request->input('direction'),
            perPage: (int) $request->input('per_page'),
            page: (int) ($request->input('page') ?? 1),
        );
    }
}
