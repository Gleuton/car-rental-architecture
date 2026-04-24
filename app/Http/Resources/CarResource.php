<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Car\Domain\Collection\CarCollection;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Shared\Application\Pagination\PaginatedResult;

class CarResource
{
    public static function CarToArray(Car $car): array
    {
        return [
            'uuid' => $car->uuid,
            'licensePlate' => $car->licensePlate(),
            'color' => $car->color(),
            'isAvailable' => $car->isAvailable(),
            'carModelUuid' => $car->carModelUuid,
            'km' => $car->km(),
        ];
    }

    /**
     * @param PaginatedResult<CarCollection> $cars
     */
    public static function PaginatedToArray(PaginatedResult $cars): array
    {
        $items = array_map(static fn (Car $car) => self::CarToArray($car), $cars->items->all());

        return [
            'data' => $items,
            'meta' => [
                'current_page' => $cars->page,
                'per_page' => $cars->perPage,
                'total' => $cars->total,
                'last_page' => $cars->lastPage,
            ],
        ];
    }
}
