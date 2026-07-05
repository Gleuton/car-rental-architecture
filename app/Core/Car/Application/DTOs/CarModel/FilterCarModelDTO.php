<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs\CarModel;

use App\Http\Requests\CarModel\IndexCarModelRequest;

readonly class FilterCarModelDTO
{
    private function __construct(
        public ?string $search,
        public ?string $brandUuid,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(IndexCarModelRequest $request): self
    {
        return new self(
            search: $request->input('search'),
            brandUuid: $request->input('brand_uuid'),
            orderBy: $request->input('order_by'),
            direction: $request->input('direction'),
            perPage: (int) $request->input('per_page'),
            page: (int) ($request->input('page') ?? 1),
        );
    }
}
