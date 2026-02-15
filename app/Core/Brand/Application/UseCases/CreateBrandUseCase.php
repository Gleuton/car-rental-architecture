<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\CreateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;

readonly class CreateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private UniqueBrandNameRule $uniqueBrandNameRule
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(CreateBrandDTO $dto): DomainBrand
    {
        $this->uniqueBrandNameRule->validate($dto->name);

        $brand = DomainBrand::new(
            $dto->name,
            $dto->image
        );

        return $this->repository->save($brand);
    }
}
