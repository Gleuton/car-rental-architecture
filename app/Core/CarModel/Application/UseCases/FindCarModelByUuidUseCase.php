<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\CarModel\Application\DTOs\CarModelUuidDTO;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

readonly class FindCarModelByUuidUseCase
{
    public function __construct(
        private CarModelRepositoryInterface $repository
    ) {}

    public function execute(CarModelUuidDTO $uuidDTO): CarModel
    {
        return $this->repository->findByUuid($uuidDTO->uuid);
    }
}
