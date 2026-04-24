<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Roles;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Errors\BrandError;

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
