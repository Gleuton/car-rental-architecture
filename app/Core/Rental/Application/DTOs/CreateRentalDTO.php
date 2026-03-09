<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\StoreRentalRequest;

readonly class CreateRentalDTO
{
    private function __construct(
        public int $carId,
        public int $clientId,
        public int $dayPriceCents,
        public string $startDate,
        public string $endDate,
        public int $initialKm,
        public int $finalKm,
    ) {}

    public static function fromRequest(StoreRentalRequest $request): self
    {
        return new self(
            $request->input('car_id'),
            $request->input('client_id'),
            $request->input('day_price_cents'),
            $request->input('start_date'),
            $request->input('end_date'),
            $request->input('initial_km'),
            $request->input('final_km'),
        );
    }
}
