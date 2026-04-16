<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

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
