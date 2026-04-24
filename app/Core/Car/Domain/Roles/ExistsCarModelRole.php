<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Roles;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;

class ExistsCarModelRole
{
    public function __construct(
        private readonly CarModelRepositoryInterface $repository
    ) {}

    /**
     * @throws CarDomainException
     */
    public function validate(string $carModelUuid): void
    {
        if (! $this->repository->existsByUuid($carModelUuid)) {
            throw new CarDomainException(CarError::MODEL_NOT_FOUND);
        }
    }
}
