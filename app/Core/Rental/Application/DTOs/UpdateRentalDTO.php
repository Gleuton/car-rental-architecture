<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\DTOs;

use App\Http\Requests\Rental\UpdateRentalRequest;

readonly class UpdateRentalDTO
{
    private function __construct(
        public int $id,
        public ?int $carId,
        public ?int $clientId,
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
            carId: $request->input('car_id'),
            clientId: $request->input('client_id'),
            dayPriceCents: $request->input('day_price_cents'),
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            initialKm: $request->input('initial_km'),
            finalKm: $request->input('final_km'),
        );
    }
}
