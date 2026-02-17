<?php

namespace App\Core\Brand\Domain\Repositories;

use App\Core\Brand\Domain\Entity\Brand;
use App\Models\Brand as EloquentBrand;
use App\Core\Brand\Domain\Entity\BrandFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BrandRepositoryInterface
{
    public function existsByName(string $name): bool;

    public function save(Brand $brand): Brand;

    /**
     * @return LengthAwarePaginator<int, EloquentBrand>
     */
    public function findByFilters(BrandFilter $filters): LengthAwarePaginator;

    public function findById(int $id): Brand;

    public function update(Brand $brand): Brand;

    public function delete(int $id): void;
}
