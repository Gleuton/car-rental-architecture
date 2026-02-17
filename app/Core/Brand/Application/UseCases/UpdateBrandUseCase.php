<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

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
            $image = LaravelUploadedFileAdapter::adapt($brandDto->imageFile);
            $imagePath = $this->storage->upload($image, 'brands')->path;
        }

        $updatedBrand = $brand->update(
            name: $brandDto->name,
            image: $imagePath
        );

        return $this->repository->update($updatedBrand);
    }
}
