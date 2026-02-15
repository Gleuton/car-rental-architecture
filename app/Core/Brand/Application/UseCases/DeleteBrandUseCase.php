<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class DeleteBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private FileStorageInterface $storage
    ) {}

    public function execute(BrandIdDTO $brandDto): void
    {
        $brand = $this->repository->findById($brandDto->id);

        $this->storage->delete($brand->image);

        $this->repository->delete($brandDto->id);
    }
}
