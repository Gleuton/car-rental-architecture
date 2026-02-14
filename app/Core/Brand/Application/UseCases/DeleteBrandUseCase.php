<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

readonly class DeleteBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository
    )
    {
    }

    public function execute(BrandIdDTO $brandDto): void
    {
        $this->repository->delete($brandDto->id);
    }
}