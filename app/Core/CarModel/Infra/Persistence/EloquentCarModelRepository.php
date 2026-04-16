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
        $brandUuid = $this->findBrandUuidById($carModel->brandId);

        $model = new EloquentCarModel;
        $model->uuid = $carModel->uuid;
        $model->brand_id = $carModel->brandId;
        $model->brand_uuid = $brandUuid;
        $model->name = $carModel->name;
        $model->image = $carModel->image;
        $model->doors = $carModel->doorsNumber;
        $model->seats = $carModel->seatsNumber;
        $model->airbags = $carModel->airbags;
        $model->abs = $carModel->abs;
        $model->save();

        return $this->toDomainCarModel($model);
    }

    public function existsByNameAndBrandId(string $name, int $brandId): bool
    {
        return EloquentCarModel::where('name', $name)
            ->where('brand_id', $brandId)
            ->exists();
    }

    /**
     * @throws CarModelDomainException
     */
    public function findById(int $id): DomainCarModel
    {
        $modelEloquent = EloquentCarModel::findOrFail($id);

        return $this->toDomainCarModel($modelEloquent);
    }

    /**
     * @throws CarModelDomainException
     */
    public function update(DomainCarModel $carModel): DomainCarModel
    {
        $brandUuid = $this->findBrandUuidById($carModel->brandId);

        $carModelEloquent = EloquentCarModel::findOrFail($carModel->id);
        $carModelEloquent->name = $carModel->name;
        $carModelEloquent->brand_id = $carModel->brandId;
        $carModelEloquent->brand_uuid = $brandUuid;
        $carModelEloquent->image = $carModel->image;
        $carModelEloquent->doors = $carModel->doorsNumber;
        $carModelEloquent->seats = $carModel->seatsNumber;
        $carModelEloquent->airbags = $carModel->airbags;
        $carModelEloquent->abs = $carModel->abs;
        $carModelEloquent->save();

        return $this->toDomainCarModel($carModelEloquent);
    }

    /**
     * @throws CarModelDomainException
     */
    private function toDomainCarModel(EloquentCarModel $carModel): DomainCarModel
    {
        return DomainCarModel::restore(
            $carModel->id,
            $carModel->brand_id,
            $carModel->name,
            $carModel->image,
            $carModel->doors,
            $carModel->seats,
            (bool) $carModel->airbags,
            (bool) $carModel->abs,
            $carModel->uuid,
        );
    }

    public function delete(int $id): void
    {
        EloquentCarModel::findOrFail($id)->delete();
    }

    private function findBrandUuidById(int $brandId): string
    {
        /** @var EloquentBrand $brand */
        $brand = EloquentBrand::query()->findOrFail($brandId);

        return $brand->uuid;
    }
}
