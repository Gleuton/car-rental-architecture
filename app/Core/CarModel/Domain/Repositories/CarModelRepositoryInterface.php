<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Repositories;

use App\Core\CarModel\Domain\Entity\CarModel;

interface CarModelRepositoryInterface
{
    public function save(CarModel $carModel): CarModel;

    public function update(CarModel $carModel): CarModel;

    public function existsByNameAndBrandId(string $name, int $brandId): bool;

    public function findById(int $id): CarModel;

    public function delete(int $id): void;
}
