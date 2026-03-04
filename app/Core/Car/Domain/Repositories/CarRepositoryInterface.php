<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Repositories;

use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Entity\CarCollection;
use App\Core\Car\Domain\Entity\CarFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface CarRepositoryInterface
{
    public function save(Car $car): Car;

    public function existsByLicensePlate(string $licensePlate): bool;

    public function findById(int $id): Car;

    /**
     * @return PaginatedResult<CarCollection>
     */
    public function listCars(CarFilter $filter): PaginatedResult;
}
