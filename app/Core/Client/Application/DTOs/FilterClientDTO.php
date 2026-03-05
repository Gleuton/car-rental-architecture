<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

use App\Http\Requests\Client\IndexClientRequest;

readonly class FilterClientDTO
{
    private function __construct(
        public ?string $search,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(IndexClientRequest $request): self
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
