<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\Brand;

use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Entities\Brand;

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
