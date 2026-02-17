<?php

namespace App\Core\CarModel\Infra;

use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Models\CarModel as EloquentCarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

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
            'abs' => $carModel->abs
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
}