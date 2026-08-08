<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\CarModel;

use App\Core\Car\Application\DTOs\CarModel\CarModelUuidDTO;
use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;

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
