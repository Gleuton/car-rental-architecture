<?php

declare(strict_types=1);

namespace App\Core\Car\Infra\Persistence;

use App\Core\Car\Domain\Collection\CarModelCollection;
use App\Core\Car\Domain\Entities\CarModel as DomainCarModel;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use App\Core\Car\Domain\Queries\CarModelQueryFilter;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\CarModel as EloquentCarModel;

class EloquentCarModelRepository implements CarModelRepositoryInterface
{
    /**
     * @return PaginatedResult<CarModelCollection>
     */
    public function findByFilters(CarModelQueryFilter $filters): PaginatedResult
    {
        $paginator = EloquentCarModel::query()
            ->with('brand')
            ->when(
                $filters->search,
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($filters->search).'%'])
            )
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage, ['*'], 'page', $filters->page);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            fn (EloquentCarModel $model) => $this->toDomainCarModel($model),
            static fn (array $items): CarModelCollection => new CarModelCollection($items)
        );
    }

    /**
     * @throws CarModelDomainException
     */
    public function save(DomainCarModel $carModel): DomainCarModel
    {
        $eloquentCarModel = EloquentCarModel::create([
            'uuid' => $carModel->uuid,
            'brand_uuid' => $carModel->brandUuid,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
        ]);

        return $this->toDomainCarModel($eloquentCarModel->load('brand'));
    }

    public function existsByNameAndBrandUuid(string $name, string $brandUuid): bool
    {
        return EloquentCarModel::where('name', $name)
            ->where('brand_uuid', $brandUuid)
            ->exists();
    }

    public function existsByUuid(string $uuid): bool
    {
        return EloquentCarModel::where('uuid', $uuid)->exists();
    }

    /**
     * @throws CarModelDomainException
     */
    public function findByUuid(string $uuid): DomainCarModel
    {
        $modelEloquent = EloquentCarModel::query()
            ->with('brand')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return $this->toDomainCarModel($modelEloquent);
    }

    /**
     * @throws CarModelDomainException
     */
    public function update(DomainCarModel $carModel): DomainCarModel
    {
        $carModelEloquent = EloquentCarModel::query()->where('uuid', $carModel->uuid)->firstOrFail();

        $carModelEloquent->update([
            'brand_uuid' => $carModel->brandUuid,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
            'uuid' => $carModel->uuid,
        ]);

        return $this->toDomainCarModel($carModelEloquent->load('brand'));
    }

    /**
     * @throws CarModelDomainException
     */
    private function toDomainCarModel(EloquentCarModel $carModel): DomainCarModel
    {
        return DomainCarModel::restore(
            $carModel->brand_uuid,
            $carModel->name,
            $carModel->image,
            $carModel->doors,
            $carModel->seats,
            (bool) $carModel->airbags,
            (bool) $carModel->abs,
            $carModel->uuid,
            $carModel->brand?->name,
        );
    }

    public function deleteByUuid(string $uuid): void
    {
        EloquentCarModel::query()->where('uuid', $uuid)->firstOrFail()->delete();
    }
}
