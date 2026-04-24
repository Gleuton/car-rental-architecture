<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\Brand;

use App\Core\Car\Application\DTOs\Brand\CreateBrandDTO;
use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Domain\Entities\Brand as DomainBrand;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Roles\UniqueBrandNameRule;

readonly class CreateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private UniqueBrandNameRule $uniqueBrandNameRule,
        private BrandLogoService $logoService
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(CreateBrandDTO $dto): DomainBrand
    {
        $this->uniqueBrandNameRule->validate($dto->name);
        $imagePath = $this->logoService->upload($dto->image, $dto->name);

        $brand = DomainBrand::create($dto->name, $imagePath);

        return $this->repository->save($brand);
    }
}
