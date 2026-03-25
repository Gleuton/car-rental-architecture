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
            'id' => $brand->id,
            'name' => $brand->name->value,
            'image' => $brand->image->path,
        ];
    }

    public static function BrandCollectionToArray(BrandCollection $brands): array
    {
        return array_map(static fn (Brand $brand) => self::BrandToArray($brand), $brands->all());
    }
}
