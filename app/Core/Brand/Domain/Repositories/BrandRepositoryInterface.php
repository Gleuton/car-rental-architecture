<?php

namespace App\Core\Brand\Domain\Repositories;

use App\Core\Brand\Application\DTOs\UpdateBrandDto;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;
use App\Core\Brand\Domain\Entity\BrandFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BrandRepositoryInterface
{
    public function existsByName(string $name): bool;

    public function save(Brand $brand): Brand;

    /**
     * @return LengthAwarePaginator<BrandCollection>
     */
    public function findByFilters(BrandFilter $filters): LengthAwarePaginator;

    public function findById(int $id): Brand;

    public function update(UpdateBrandDto $brandDto): Brand;

    public function delete(int $id): void;
}
