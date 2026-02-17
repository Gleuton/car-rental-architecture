<?php

namespace App\Core\Brand\Infra\Mappers;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Models\Brand as EloquentBrand;
use App\Core\Brand\Domain\Entity\Brand as DomainBrand;

final class EloquentBrandMapper
{
    /**
     * @throws BrandDomainException
     */
    public static function toDomain(EloquentBrand $model): DomainBrand
    {
        return DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image
        );
    }
}