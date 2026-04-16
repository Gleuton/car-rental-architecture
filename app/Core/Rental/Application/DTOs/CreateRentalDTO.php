<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\StoreRentalRequest;

readonly class CreateRentalDTO
{
    private function __construct(
        public ?int $carId,
        public ?string $carUuid,
        public ?int $clientId,
        public ?string $clientUuid,
        public int $dayPriceCents,
        public string $startDate,
        public string $endDate,
        public int $initialKm,
        public int $finalKm,
    ) {}

    public static function fromRequest(StoreRentalRequest $request): self
    {
        return new self(
            $request->has('car_id') ? $request->integer('car_id') : null,
            $request->input('car_uuid'),
            $request->has('client_id') ? $request->integer('client_id') : null,
            $request->input('client_uuid'),
            $request->integer('day_price_cents'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->integer('initial_km'),
            $request->integer('final_km'),
        );
    }
}
