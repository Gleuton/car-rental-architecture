<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Repositories;

use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Entity\RentalCollection;
use App\Core\Rental\Domain\Entity\RentalFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface RentalRepositoryInterface
{
    public function save(Rental $rental): Rental;

    public function findById(int $id): Rental;

    public function delete(int $id): void;

    /**
     * @return PaginatedResult<RentalCollection>
     */
    public function findByFilters(RentalFilter $filters): PaginatedResult;
}
