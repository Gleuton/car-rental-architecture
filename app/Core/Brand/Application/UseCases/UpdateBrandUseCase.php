<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

readonly class UpdateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private FileStorageInterface $storage,
        private UniqueBrandNameRule $uniqueBrandNameRule
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(UpdateBrandDTO $brandDto): Brand
    {
        if ($brandDto->name) {
            $this->uniqueBrandNameRule->validate($brandDto->name);
        }

        $brand = $this->repository->findById($brandDto->id);

        $imagePath = $brand->imagePath();
        $imagePath = $this->updateImage($brandDto, $brand, $imagePath);

        $brand->changeLogo($imagePath)
            ->rename($brandDto->name);

        return $this->repository->update($brand);
    }

    private function updateImage(UpdateBrandDTO $brandDto, Brand $brand, string $imagePath): string
    {
        if ($brandDto->imageFile) {
            $image = LaravelUploadedFileAdapter::adapt($brandDto->imageFile);
            $imagePath = $this->storage->upload($image, 'brands')->path;
            $this->storage->delete($brand->imagePath());
        }

        return $imagePath;
    }
}
