<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\CarModel\Application\DTOs\CarModelIdDTO;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class DeleteCarModelUseCase
{
    public function __construct(
        private CarModelRepositoryInterface $repository,
        private FileStorageInterface $storage
    ) {}

    public function execute(CarModelIdDTO $brandDto): void
    {
        $brand = $this->repository->findById($brandDto->id);

        $this->storage->delete($brand->image);

        $this->repository->delete($brandDto->id);
    }
}
