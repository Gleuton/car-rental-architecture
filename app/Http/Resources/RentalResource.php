<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Entity\RentalCollection;
use App\Core\Shared\Application\Pagination\PaginatedResult;

class RentalResource
{
    public static function toArray(Rental $rental): array
    {
        return [
            'uuid' => $rental->uuid,
            'carId' => $rental->carId,
            'clientId' => $rental->clientId,
            'dayPriceCents' => $rental->dayPriceCents,
            'startDate' => $rental->startDate,
            'endDate' => $rental->endDate,
            'initialKm' => $rental->initialKm,
            'finalKm' => $rental->finalKm,
            'totalPrice' => $rental->totalPrice,
        ];
    }

    /**
     * @param PaginatedResult<RentalCollection> $rentals
     */
    public static function PaginatedToArray(PaginatedResult $rentals): array
    {
        $items = array_map(static fn (Rental $rental) => self::toArray($rental), $rentals->items->all());

        return [
            'data' => $items,
            'meta' => [
                'current_page' => $rentals->page,
                'per_page' => $rentals->perPage,
                'total' => $rentals->total,
                'last_page' => $rentals->lastPage,
            ],
        ];
    }
}
