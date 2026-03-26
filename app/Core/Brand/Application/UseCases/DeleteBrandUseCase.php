<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Application\Services\BrandLogoService;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

readonly class DeleteBrandUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private BrandLogoService $logoService
    ) {}

    public function execute(BrandIdDTO $brandDto): void
    {
        $brand = $this->repository->findById($brandDto->id);

        $this->logoService->delete($brand->imagePath());

        $this->repository->delete($brandDto->id);
    }
}
