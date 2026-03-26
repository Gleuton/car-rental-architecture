<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Application\Services\BrandLogoService;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;

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
        if ($brandDto->name) {
            $this->uniqueBrandNameRule->validate($brandDto->name);
        }

        $brand = $this->repository->findById($brandDto->id);
        $imagePath = $brand->imagePath();

        if ($brandDto->imageFile) {
            $imagePath = $this->logoService->replace($brandDto->imageFile, $brand->imagePath());
        }

        $brand->changeLogo($imagePath)
            ->rename($brandDto->name);

        return $this->repository->update($brand);
    }
}
