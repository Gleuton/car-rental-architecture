<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\Brand;

use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

readonly class DeleteBrandByUuidUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
        private BrandLogoService $logoService
    ) {}

    public function execute(string $uuid): void
    {
        $brand = $this->repository->findByUuid($uuid);

        $this->logoService->delete($brand->imagePath());

        $this->repository->deleteByUuid($uuid);
    }
}
