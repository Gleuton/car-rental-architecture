<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\Brand;

use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Car\Application\DTOs\Brand\UpdateBrandDTO;
use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Domain\Entities\Brand;
use App\Core\Car\Domain\Exceptions\BrandDomainException;

readonly class UpdateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private UniqueBrandNameRule $uniqueBrandNameRule,
        private BrandLogoService $logoService
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(UpdateBrandDTO $brandDto): Brand
    {
        $brand = $this->repository->findByUuid($brandDto->uuid);

        if ($brandDto->name && $brandDto->name !== $brand->name()) {
            $this->uniqueBrandNameRule->validate($brandDto->name);
        }

        $imagePath = $brand->imagePath();

        if ($brandDto->imageFile) {
            $brandNameForLogo = $brandDto->name ?? $brand->name();
            $imagePath = $this->logoService->replace($brandDto->imageFile, $brand->imagePath(), $brandNameForLogo);
        }

        $brand->changeLogo($imagePath)
            ->rename($brandDto->name);

        return $this->repository->update($brand);
    }
}
