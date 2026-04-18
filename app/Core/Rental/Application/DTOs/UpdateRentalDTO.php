<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\UpdateRentalRequest;

readonly class UpdateRentalDTO
{
    private function __construct(
        public string $uuid,
        public ?string $carUuid,
        public ?string $clientUuid,
        public ?int $dayPriceCents,
        public ?string $startDate,
        public ?string $endDate,
        public ?int $initialKm,
        public ?int $finalKm,
    ) {}

    public static function fromRequest(UpdateRentalRequest $request, string $rental): self
    {
        return new self(
            uuid: $rental,
            carUuid: $request->input('car_uuid'),
            clientUuid: $request->input('client_uuid'),
            dayPriceCents: $request->has('day_price_cents') ? $request->integer('day_price_cents') : null,
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            initialKm: $request->has('initial_km') ? $request->integer('initial_km') : null,
            finalKm: $request->has('final_km') ? $request->integer('final_km') : null,
        );
    }
}
