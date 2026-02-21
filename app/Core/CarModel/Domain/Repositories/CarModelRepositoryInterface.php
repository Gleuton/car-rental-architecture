<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Repositories;

use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Entity\CarModelCollection;
use App\Core\CarModel\Domain\Entity\CarModelFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface CarModelRepositoryInterface
{
    public function save(CarModel $carModel): CarModel;

    public function update(CarModel $carModel): CarModel;

    /**
     * @return PaginatedResult<CarModelCollection>
     */
    public function findByFilters(CarModelFilter $filters): PaginatedResult;

    public function existsByNameAndBrandId(string $name, int $brandId): bool;

    public function findById(int $id): CarModel;

    public function delete(int $id): void;
}
