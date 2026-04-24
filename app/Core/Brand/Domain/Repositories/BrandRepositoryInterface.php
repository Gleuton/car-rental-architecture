<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Repositories;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Query\BrandQueryFilter;
use App\Core\Car\Domain\Collection\BrandCollection;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface BrandRepositoryInterface
{
    public function existsByName(string $name): bool;

    public function save(Brand $brand): Brand;

    /**
     * @return PaginatedResult<BrandCollection>
     */
    public function findByFilters(BrandQueryFilter $filters): PaginatedResult;

    public function findByUuid(string $uuid): Brand;

    public function update(Brand $brand): Brand;

    public function deleteByUuid(string $uuid): void;

    public function existsByUuid(string $brandUuid): bool;
}
