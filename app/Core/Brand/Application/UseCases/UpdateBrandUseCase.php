<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class UpdateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private FileStorageInterface $storage
    ) {}

    public function execute(UpdateBrandDTO $brandDto): Brand
    {
        $brand = $this->repository->findById($brandDto->id);

        $imagePath = $brand->image;
        if ($brandDto->imageFile) {
            $this->storage->delete($brand->image);
            $imagePath = $this->storage->upload($brandDto->imageFile, 'brands');
        }

        $updatedBrand = $brand->update(
            name: $brandDto->name,
            image: $imagePath
        );

        return $this->repository->update($updatedBrand);
    }
}
