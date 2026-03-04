<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Repositories;

use App\Core\Car\Domain\Entity\Car;

interface CarRepositoryInterface
{
    public function save(Car $car): Car;

    public function existsByLicensePlate(string $licensePlate): bool;

    public function findById(int $id): Car;
}
