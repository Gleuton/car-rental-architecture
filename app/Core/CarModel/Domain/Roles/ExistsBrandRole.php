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
    public function validate(string $brandUuid): void
    {
        if (! $this->repository->existsByUuid($brandUuid)) {
            throw new BrandDomainException(BrandError::NOT_FOUND);
        }
    }
}
