<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Roles;

use App\Core\Brand\Domain\Errors\BrandError;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

class UniqueBrandNameRule
{
    public function __construct(
        private readonly BrandRepositoryInterface $repository
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function validate(string $name): void
    {
        if ($this->repository->existsByName($name)) {
            throw new BrandDomainException(BrandError::ALREADY_EXISTS);
        }
    }
}
