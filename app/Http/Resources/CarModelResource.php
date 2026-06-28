<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Car\Domain\Entities\CarModel;

class CarModelResource
{
    public static function toArray(CarModel $carModel): array
    {
        return [
            'uuid' => $carModel->uuid,
            'brandName' => $carModel->brandName(),
            'name' => $carModel->name,
            'image' => $carModel->image,
            'doorsNumber' => $carModel->doorsNumber,
            'seatsNumber' => $carModel->seatsNumber,
            'airbags' => $carModel->airbags,
            'abs' => $carModel->abs,
        ];
    }
}
