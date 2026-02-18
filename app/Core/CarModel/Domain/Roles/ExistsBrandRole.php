<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Roles;

use App\Core\Brand\Domain\Errors\BrandError;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

class ExistsBrandRole
{
    public function __construct(private readonly BrandRepositoryInterface $repository) {}

    /**
     * @throws BrandDomainException
     */
    public function validate(int $brandId): void
    {
        if (! $this->repository->exists($brandId)) {
            throw new BrandDomainException(BrandError::NOT_FOUND);
        }
    }
}
