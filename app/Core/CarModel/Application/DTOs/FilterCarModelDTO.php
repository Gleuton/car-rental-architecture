<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\DTOs;

use App\Http\Requests\CarModel\IndexCarModelRequest;

class FilterCarModelDTO
{
    private function __construct(
        public ?string $search,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(IndexCarModelRequest $request): self
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
