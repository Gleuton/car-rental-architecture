<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\CarModel;

use App\Core\Car\Application\DTOs\CarModel\CarModelUuidDTO;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
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
