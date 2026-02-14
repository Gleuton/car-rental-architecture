<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\UpdateBrandDto;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

readonly class UpdateBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository
    )
    {
    }

    public function execute(UpdateBrandDto $brandDto): Brand
    {
        return $this->repository->update($brandDto);
    }
}