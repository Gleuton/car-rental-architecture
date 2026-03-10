<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Repositories;

use App\Core\Rental\Domain\Entity\Rental;

interface RentalRepositoryInterface
{
    public function save(Rental $rental): Rental;
}
