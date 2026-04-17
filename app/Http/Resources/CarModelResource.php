<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\CarModel\Domain\Entity\CarModel;

class CarModelResource
{
    public static function toArray(CarModel $carModel): array
    {
        return [
            'uuid' => $carModel->uuid,
            'brandUuid' => $carModel->brandUuid,
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doorsNumber' => $carModel->doorsNumber,
            'seatsNumber' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
        ];
    }
}
