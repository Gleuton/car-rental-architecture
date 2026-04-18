<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Roles;

use App\Core\CarModel\Domain\Errors\CarModelError;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

class CarModelAlreadyExistsRole
{
    public function __construct(
        private CarModelRepositoryInterface $repository
    ) {}

    /**
     * @throws CarModelDomainException
     */
    public function validate(string $name, string $brandUuid): void
    {
        if ($this->repository->existsByNameAndBrandUuid($name, $brandUuid)) {
            throw new CarModelDomainException(CarModelError::ALREADY_EXISTS);
        }
    }
}
