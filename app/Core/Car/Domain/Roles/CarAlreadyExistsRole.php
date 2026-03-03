<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Roles;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

class CarAlreadyExistsRole
{
    public function __construct(
        private CarRepositoryInterface $repository
    ) {}

    /**
     * @throws CarDomainException
     */
    public function validate(string $licensePlate): void
    {
        if ($this->repository->existsByLicensePlate($licensePlate)) {
            throw new CarDomainException(CarError::ALREADY_EXISTS);
        }
    }
}
