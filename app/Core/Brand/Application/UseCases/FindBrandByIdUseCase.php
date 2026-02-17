<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

readonly class FindBrandByIdUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository
    ) {}

    public function execute(BrandIdDTO $brandDTO): Brand
    {
        return $this->repository->findById($brandDTO->id);
    }
}
