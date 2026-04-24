<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Roles;

use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

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
