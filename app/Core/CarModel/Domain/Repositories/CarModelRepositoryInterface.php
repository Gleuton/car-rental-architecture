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

    public function existsByNameAndBrandUuid(string $name, string $brandUuid): bool;

    public function existsByUuid(string $uuid): bool;

    public function findByUuid(string $uuid): CarModel;

    public function deleteByUuid(string $uuid): void;
}
