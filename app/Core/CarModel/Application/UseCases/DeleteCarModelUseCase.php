<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\CarModel\Application\DTOs\CarModelUuidDTO;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class DeleteCarModelUseCase
{
    public function __construct(
        private CarModelRepositoryInterface $repository,
        private FileStorageInterface $storage
    ) {}

    public function execute(CarModelUuidDTO $carModelDto): void
    {
        $carModel = $this->repository->findByUuid($carModelDto->uuid);

        $this->storage->delete($carModel->image);

        $this->repository->deleteByUuid($carModelDto->uuid);
    }
}
