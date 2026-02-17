<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\CreateBrandDTO;
use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class CreateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private UniqueBrandNameRule $uniqueBrandNameRule,
        private FileStorageInterface $storage
    ) {}

    /**
     * @throws BrandDomainException
     */
    public function execute(CreateBrandDTO $dto): DomainBrand
    {
        $this->uniqueBrandNameRule->validate($dto->name);

        $imagePath = $this->storage->upload($dto->image, 'brands');

        $brand = DomainBrand::new(
            $dto->name,
            $imagePath
        );

        return $this->repository->save($brand);
    }
}
