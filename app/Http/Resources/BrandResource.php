<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;

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

    public static function BrandCollectionToArray(BrandCollection $brands): array
    {
        return array_map(static fn (Brand $brand) => self::BrandToArray($brand), $brands->all());
    }
}
