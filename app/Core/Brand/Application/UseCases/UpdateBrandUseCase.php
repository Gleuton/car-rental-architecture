<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

readonly class UpdateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private FileStorageInterface $storage
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(UpdateBrandDTO $brandDto): Brand
    {
        $brand = $this->repository->findById($brandDto->id);

        $imagePath = $brand->image;
        $imagePath = $this->updateImage($brandDto, $brand, $imagePath);

        $updatedBrand = $brand->update(
            name: $brandDto->name,
            image: $imagePath
        );

        return $this->repository->update($updatedBrand);
    }

    private function updateImage(UpdateBrandDTO $brandDto, Brand $brand, string $imagePath): string
    {
        if ($brandDto->imageFile) {
            $this->storage->delete($brand->image);
            $image = LaravelUploadedFileAdapter::adapt($brandDto->imageFile);
            $imagePath = $this->storage->upload($image, 'brands')->path;
        }

        return $imagePath;
    }
}
