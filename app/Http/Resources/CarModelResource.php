<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Car\Domain\Collection\CarModelCollection;
use App\Core\Car\Domain\Entities\CarModel;
use App\Core\Shared\Application\Pagination\PaginatedResult;

class CarModelResource
{
    /**
     * @param PaginatedResult<CarModelCollection> $carModels
     */
    public static function paginateToArray(PaginatedResult $carModels): array
    {
        $items = array_map(static fn (CarModel $carModel) => self::toArray($carModel), $carModels->items->all());

        return [
            'data' => $items,
            'meta' => [
                'current_page' => $carModels->page,
                'per_page' => $carModels->perPage,
                'total' => $carModels->total,
                'last_page' => $carModels->lastPage,
            ],
        ];
    }

    public static function toArray(CarModel $carModel): array
    {
        return [
            'uuid' => $carModel->uuid,
            'brandUuid' => $carModel->brandUuid,
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
