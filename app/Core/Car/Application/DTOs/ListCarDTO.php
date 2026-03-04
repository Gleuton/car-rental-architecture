<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs;

use App\Http\Requests\Car\IndexCarRequest;

class ListCarDTO
{
    private function __construct(
        public ?string $licensePlate,
        public string $orderBy,
        public string $direction,
        public int $perPage,
        public int $page,
    ) {}

    public static function fromRequest(IndexCarRequest $request): self
    {
        return new self(
            licensePlate: $request->input('license_plate'),
            orderBy: $request->input('order_by'),
            direction: $request->input('direction'),
            perPage: (int) $request->input('per_page'),
            page: (int) ($request->input('page') ?? 1),
        );
    }
}
