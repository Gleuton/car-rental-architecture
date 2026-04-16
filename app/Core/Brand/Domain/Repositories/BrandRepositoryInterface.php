<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Repositories;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface BrandRepositoryInterface
{
    public function existsByName(string $name): bool;

    public function save(Brand $brand): Brand;

    /**
     * @return PaginatedResult<BrandCollection>
     */
    public function findByFilters(BrandFilter $filters): PaginatedResult;

    public function findById(int $id): Brand;

    public function findByUuid(string $uuid): Brand;

    public function update(Brand $brand): Brand;

    public function delete(int $id): void;

    public function deleteByUuid(string $uuid): void;

    public function exists(int $brandId): bool;
}
