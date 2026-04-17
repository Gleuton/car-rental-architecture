<?php

declare(strict_types=1);

namespace App\Core\CarModel\Infra\Persistence;

use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Core\CarModel\Domain\Entity\CarModelCollection;
use App\Core\CarModel\Domain\Entity\CarModelFilter;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Brand as EloquentBrand;
use App\Models\CarModel as EloquentCarModel;

class EloquentCarModelRepository implements CarModelRepositoryInterface
{
    /**
     * @return PaginatedResult<CarModelCollection>
     */
    public function findByFilters(CarModelFilter $filters): PaginatedResult
    {
        $paginator = EloquentCarModel::query()
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
        $brandId = $this->findBrandIdByUuid($carModel->brandUuid);

        $eloquentCarModel = EloquentCarModel::create([
            'uuid' => $carModel->uuid,
            'brand_id' => $brandId,
            'brand_uuid' => $carModel->brandUuid,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
        ]);

        return $this->toDomainCarModel($eloquentCarModel);
    }

    public function existsByNameAndBrandUuid(string $name, string $brandUuid): bool
    {
        $brandId = EloquentBrand::query()->where('uuid', $brandUuid)->value('id');

        return EloquentCarModel::where('name', $name)
            ->where(static function ($query) use ($brandUuid, $brandId) {
                $query->where('brand_uuid', $brandUuid);

                if ($brandId !== null) {
                    $query->orWhere('brand_id', (int) $brandId);
                }
            })
            ->exists();
    }

    /**
     * @throws CarModelDomainException
     */
    public function findByUuid(string $uuid): DomainCarModel
    {
        $modelEloquent = EloquentCarModel::query()->where('uuid', $uuid)->firstOrFail();

        return $this->toDomainCarModel($modelEloquent);
    }

    /**
     * @throws CarModelDomainException
     */
    public function update(DomainCarModel $carModel): DomainCarModel
    {
        $brandId = $this->findBrandIdByUuid($carModel->brandUuid);

        $carModelEloquent = EloquentCarModel::query()->where('uuid', $carModel->uuid)->firstOrFail();

        $carModelEloquent->update([
            'brand_id' => $brandId,
            'brand_uuid' => $carModel->brandUuid,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
            'uuid' => $carModel->uuid,
        ]);

        return $this->toDomainCarModel($carModelEloquent);
    }

    /**
     * @throws CarModelDomainException
     */
    private function toDomainCarModel(EloquentCarModel $carModel): DomainCarModel
    {
        return DomainCarModel::restore(
            $carModel->id,
            $carModel->brand_uuid,
            $carModel->name,
            $carModel->image,
            $carModel->doors,
            $carModel->seats,
            (bool) $carModel->airbags,
            (bool) $carModel->abs,
            $carModel->uuid,
        );
    }

    public function deleteByUuid(string $uuid): void
    {
        EloquentCarModel::query()->where('uuid', $uuid)->firstOrFail()->delete();
    }

    private function findBrandIdByUuid(string $brandUuid): int
    {
        /** @var EloquentBrand $brand */
        $brand = EloquentBrand::query()->where('uuid', $brandUuid)->firstOrFail();

        return $brand->id;
    }
}
