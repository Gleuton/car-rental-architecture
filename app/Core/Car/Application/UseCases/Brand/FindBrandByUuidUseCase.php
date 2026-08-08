<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\Brand;

use App\Core\Car\Domain\Entity\Brand;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

readonly class FindBrandByUuidUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository
    ) {}

    public function execute(string $uuid): Brand
    {
        return $this->repository->findByUuid($uuid);
    }
}
