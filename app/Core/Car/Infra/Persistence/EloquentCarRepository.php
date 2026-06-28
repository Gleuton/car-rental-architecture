<?php

declare(strict_types=1);

namespace App\Core\Car\Infra\Persistence;

use App\Core\Car\Domain\Collection\CarCollection;
use App\Core\Car\Domain\Entities\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Queries\CarQueryFilter;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Car as EloquentCar;

class EloquentCarRepository implements CarRepositoryInterface
{
    /**
     * @throws CarDomainException
     */
    public function save(Car $car): Car
    {
        $eloquentCar = EloquentCar::create([
            'uuid' => $car->uuid,
            'car_model_uuid' => $car->carModelUuid,
            'license_plate' => $car->licensePlate(),
            'color' => $car->color(),
            'is_available' => $car->isAvailable(),
            'km' => $car->km(),
        ]);

        return $this->toDomainCar($eloquentCar->load('carModel.brand'));
    }

    public function existsByLicensePlate(string $licensePlate): bool
    {
        return EloquentCar::where('license_plate', $licensePlate)->exists();
    }

    /**
     * @throws CarDomainException
     */
    public function findByUuid(string $uuid): Car
    {
        $eloquentCar = EloquentCar::query()
            ->with('carModel.brand')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return $this->toDomainCar($eloquentCar);
    }

    /**
     * @return PaginatedResult<CarCollection>
     */
    public function listCars(CarQueryFilter $filter): PaginatedResult
    {
        $query = EloquentCar::query()->with('carModel.brand');

        if ($filter->licensePlate !== null) {
            $query->where('license_plate', 'like', '%'.$filter->licensePlate.'%');
        }

        $eloquentCars = $query
            ->orderBy($filter->orderBy, $filter->direction)
            ->paginate($filter->perPage, ['*'], 'page', $filter->page);

        return LaravelPaginatorAdapter::adapt(
            $eloquentCars,
            fn (EloquentCar $eloquentCar) => $this->toDomainCar($eloquentCar),
            static fn (array $cars) => new CarCollection($cars),
        );
    }

    public function deleteByUuid(string $uuid): void
    {
        EloquentCar::query()->where('uuid', $uuid)->firstOrFail()->delete();
    }

    /**
     * @throws CarDomainException
     */
    private function toDomainCar(EloquentCar $eloquentCar): Car
    {
        return Car::restore(
            $eloquentCar->car_model_uuid,
            $eloquentCar->license_plate,
            $eloquentCar->color,
            (bool) $eloquentCar->is_available,
            $eloquentCar->km,
            $eloquentCar->uuid,
            $eloquentCar->carModel?->name,
            $eloquentCar->carModel?->brand?->name,
        );
    }

    /**
     * @throws CarDomainException
     */
    public function update(Car $car): Car
    {
        $eloquentCar = EloquentCar::query()->where('uuid', $car->uuid)->firstOrFail();

        $eloquentCar->update([
            'car_model_uuid' => $car->carModelUuid,
            'license_plate' => $car->licensePlate(),
            'color' => $car->color(),
            'is_available' => $car->isAvailable(),
            'km' => $car->km(),
        ]);

        return $this->toDomainCar($eloquentCar->load('carModel.brand'));
    }
}
