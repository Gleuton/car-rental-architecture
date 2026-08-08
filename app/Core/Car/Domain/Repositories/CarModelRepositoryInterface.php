<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Repositories;

use App\Core\Car\Domain\Collection\CarModelCollection;
use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Car\Domain\Queries\CarModelQueryFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface CarModelRepositoryInterface
{
    public function save(CarModel $carModel): CarModel;

    public function update(CarModel $carModel): CarModel;

    /**
     * @return PaginatedResult<CarModelCollection>
     */
    public function findByFilters(CarModelQueryFilter $filters): PaginatedResult;

    public function findByBrandUuid(string $brandUuid): CarModelCollection;

    public function existsByNameAndBrandUuid(string $name, string $brandUuid): bool;

    public function existsByUuid(string $uuid): bool;

    public function findByUuid(string $uuid): CarModel;

    public function deleteByUuid(string $uuid): void;
}
