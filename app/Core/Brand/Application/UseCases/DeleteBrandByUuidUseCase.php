<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Application\Services\BrandLogoService;

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
