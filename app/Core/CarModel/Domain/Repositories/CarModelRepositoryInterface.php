<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Repositories;

use App\Core\CarModel\Domain\Entity\CarModel;

interface CarModelRepositoryInterface
{
    public function save(CarModel $carModel): CarModel;
}
