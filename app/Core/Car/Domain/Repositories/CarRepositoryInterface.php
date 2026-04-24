<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Repositories;

use App\Core\Car\Domain\Collection\CarCollection;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Entity\CarFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface CarRepositoryInterface
{
    public function save(Car $car): Car;

    public function existsByLicensePlate(string $licensePlate): bool;

    public function findByUuid(string $uuid): Car;

    /**
     * @return PaginatedResult<CarCollection>
     */
    public function listCars(CarFilter $filter): PaginatedResult;

    public function deleteByUuid(string $uuid): void;

    public function update(Car $car): Car;
}
