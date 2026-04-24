<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Roles;

use App\Core\Car\Domain\Errors\CarModelError;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;

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
