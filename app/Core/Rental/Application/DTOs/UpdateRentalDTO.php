<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\UpdateRentalRequest;

readonly class UpdateRentalDTO
{
    private function __construct(
        public int $id,
        public ?int $carId,
        public ?string $carUuid,
        public ?int $clientId,
        public ?string $clientUuid,
        public ?int $dayPriceCents,
        public ?string $startDate,
        public ?string $endDate,
        public ?int $initialKm,
        public ?int $finalKm,
    ) {}

    public static function fromRequest(UpdateRentalRequest $request, int $rentalId): self
    {
        return new self(
            id: $rentalId,
            carId: $request->has('car_id') ? $request->integer('car_id') : null,
            carUuid: $request->input('car_uuid'),
            clientId: $request->has('client_id') ? $request->integer('client_id') : null,
            clientUuid: $request->input('client_uuid'),
            dayPriceCents: $request->has('day_price_cents') ? $request->integer('day_price_cents') : null,
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            initialKm: $request->has('initial_km') ? $request->integer('initial_km') : null,
            finalKm: $request->has('final_km') ? $request->integer('final_km') : null,
        );
    }
}
