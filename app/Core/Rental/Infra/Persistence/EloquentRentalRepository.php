<?php

declare(strict_types=1);

namespace App\Core\Rental\Infra\Persistence;

use App\Core\Rental\Domain\Entity\Rental as DomainRental;
use App\Core\Rental\Domain\Entity\RentalCollection;
use App\Core\Rental\Domain\Entity\RentalFilter;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Rental as EloquentRental;

class EloquentRentalRepository implements RentalRepositoryInterface
{
    public function save(DomainRental $rental): DomainRental
    {
        $model = EloquentRental::create([
            'uuid' => $rental->uuid,
            'car_uuid' => $rental->carUuid,
            'client_uuid' => $rental->clientUuid,
            'day_price_cents' => $rental->dayPriceCents,
            'start_date' => $rental->startDate,
            'end_date' => $rental->endDate,
            'initial_km' => $rental->initialKm,
            'final_km' => $rental->finalKm,
        ]);

        return $this->toDomain($model);
    }

    public function findByUuid(string $uuid): DomainRental
    {
        $model = EloquentRental::query()->where('uuid', $uuid)->firstOrFail();

        return $this->toDomain($model);
    }

    public function deleteByUuid(string $uuid): void
    {
        EloquentRental::query()->where('uuid', $uuid)->firstOrFail()->delete();
    }

    public function update(DomainRental $rental): DomainRental
    {
        $model = EloquentRental::query()->where('uuid', $rental->uuid)->firstOrFail();

        $model->update([
            'car_uuid' => $rental->carUuid,
            'client_uuid' => $rental->clientUuid,
            'day_price_cents' => $rental->dayPriceCents,
            'start_date' => $rental->startDate,
            'end_date' => $rental->endDate,
            'initial_km' => $rental->initialKm,
            'final_km' => $rental->finalKm,
        ]);

        return $this->toDomain($model);
    }

    /**
     * @return PaginatedResult<RentalCollection>
     */
    public function findByFilters(RentalFilter $filters): PaginatedResult
    {
        $paginator = EloquentRental::query()
            ->when($filters->startDateFrom, fn ($q) => $q->where('start_date', '>=', $filters->startDateFrom))
            ->when($filters->startDateTo, fn ($q) => $q->where('start_date', '<=', $filters->startDateTo))
            ->when($filters->endDateFrom, fn ($q) => $q->where('end_date', '>=', $filters->endDateFrom))
            ->when($filters->endDateTo, fn ($q) => $q->where('end_date', '<=', $filters->endDateTo))
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage, ['*'], 'page', $filters->page);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            fn (EloquentRental $model) => $this->toDomain($model),
            static fn (array $items): RentalCollection => new RentalCollection($items),
        );
    }

    private function toDomain(EloquentRental $model): DomainRental
    {
        return DomainRental::restore(
            $model->car_uuid,
            $model->client_uuid,
            $model->day_price_cents,
            $model->start_date->format('Y-m-d H:i:s'),
            $model->end_date->format('Y-m-d H:i:s'),
            $model->initial_km,
            $model->final_km,
            $model->uuid,
        );
    }
}
