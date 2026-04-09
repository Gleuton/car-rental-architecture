<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;
use App\Core\Shared\Application\Pagination\PaginatedResult;

class BrandResource
{
    public static function BrandToArray(Brand $brand): array
    {
        return [
            'id' => $brand->id(),
            'name' => $brand->name(),
            'image' => $brand->imagePath(),
        ];
    }

    /**
     * @param PaginatedResult<BrandCollection> $brands
     */
    public static function PaginatedToArray(PaginatedResult $brands): array
    {
        $items = array_map(static fn (Brand $brand) => self::BrandToArray($brand), $brands->items->all());

        return [
            'data' => $items,
            'meta' => [
                'current_page' => $brands->page,
                'per_page' => $brands->perPage,
                'total' => $brands->total,
                'last_page' => $brands->lastPage,
            ],
        ];
    }
}
