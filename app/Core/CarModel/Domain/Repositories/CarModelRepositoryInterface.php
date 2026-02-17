<?php

namespace App\Core\CarModel\Domain\Repositories;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\CarModel\Domain\Entity\CarModel;

interface CarModelRepositoryInterface
{
    public function save(CarModel $carModel): CarModel;
}
