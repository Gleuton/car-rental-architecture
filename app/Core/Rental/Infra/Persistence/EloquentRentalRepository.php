<?php

declare(strict_types=1);

namespace App\Core\Rental\Infra\Persistence;

use App\Core\Rental\Domain\Entity\Rental as DomainRental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Models\Rental as EloquentRental;

class EloquentRentalRepository implements RentalRepositoryInterface
{
    public function save(DomainRental $rental): DomainRental
    {
        $model = EloquentRental::create([
            'car_id' => $rental->carId,
            'client_id' => $rental->clientId,
            'day_price_cents' => $rental->dayPriceCents,
            'start_date' => $rental->startDate,
            'end_date' => $rental->endDate,
            'initial_km' => $rental->initialKm,
            'final_km' => $rental->finalKm,
        ]);

        return $this->toDomain($model);
    }

    private function toDomain(EloquentRental $model): DomainRental
    {
        return DomainRental::restore(
            $model->id,
            $model->car_id,
            $model->client_id,
            $model->day_price_cents,
            $model->start_date->format('Y-m-d H:i:s'),
            $model->end_date->format('Y-m-d H:i:s'),
            $model->initial_km,
            $model->final_km,
        );
    }
}
