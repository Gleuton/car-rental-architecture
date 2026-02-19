<?php

declare(strict_types=1);

namespace App\Core\CarModel\Infra;

use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Models\CarModel as EloquentCarModel;

class EloquentCarModelRepository implements CarModelRepositoryInterface
{
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

        return DomainCarModel::restore(
            $model->id,
            $model->brand_id,
            $model->name,
            $model->image,
            $model->doors,
            $model->seats,
            $model->airbags,
            $model->abs
        );
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

        return DomainCarModel::restore(
            $modelEloquent->id,
            $modelEloquent->brand_id,
            $modelEloquent->name,
            $modelEloquent->image,
            $modelEloquent->doors,
            $modelEloquent->seats,
            (bool) $modelEloquent->airbags,
            (bool) $modelEloquent->abs
        );
    }
}
