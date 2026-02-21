<?php

declare(strict_types=1);

namespace App\Core\CarModel\Infra;

use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Core\CarModel\Domain\Entity\CarModelCollection;
use App\Core\CarModel\Domain\Entity\CarModelFilter;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
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
        $model = EloquentCarModel::create([
            'brand_id' => $carModel->brandId,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
        ]);

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
        $carModelEloquent = EloquentCarModel::findOrFail($carModel->id);
        $carModelEloquent->update([
            'name' => $carModel->name,
            'brand_id' => $carModel->brandId,
            'image' => $carModel->image,
            'doors' => $carModel->doorsNumber,
            'seats' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
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
            $carModel->brand_id,
            $carModel->name,
            $carModel->image,
            $carModel->doors,
            $carModel->seats,
            (bool) $carModel->airbags,
            (bool) $carModel->abs
        );
    }

    public function delete(int $id): void
    {
        EloquentCarModel::findOrFail($id)->delete();
    }
}
