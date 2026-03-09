<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Models\Rental;

class CreateRentalUseCase
{
    public function execute(CreateRentalDTO $dto)
    {
        return Rental::create([
            'client_id' => $dto->clientId,
            'car_id' => $dto->carId,
            'start_date' => $dto->startDate,
            'end_date' => $dto->endDate,
            'initial_km' => $dto->initialKm,
            'final_km' => $dto->finalKm,
            'day_price_cents' => $dto->dayPriceCents,
        ]);
    }
}
