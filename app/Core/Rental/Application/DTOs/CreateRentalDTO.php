<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\StoreRentalRequest;

readonly class CreateRentalDTO
{
    private function __construct(
        public string $carUuid,
        public string $clientUuid,
        public int $dayPriceCents,
        public string $startDate,
        public string $endDate,
        public int $initialKm,
        public int $finalKm,
    ) {}

    public static function fromRequest(StoreRentalRequest $request): self
    {
        return new self(
            $request->string('car_uuid')->toString(),
            $request->string('client_uuid')->toString(),
            $request->integer('day_price_cents'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->integer('initial_km'),
            $request->integer('final_km'),
        );
    }
}
