<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\FilterRentalDTO;
use App\Core\Rental\Domain\Entity\RentalCollection;
use App\Core\Rental\Domain\Entity\RentalFilter;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListRentalsUseCase
{
    public function __construct(
        private RentalRepositoryInterface $repository,
    ) {}

    /**
     * @return PaginatedResult<RentalCollection>
     */
    public function execute(FilterRentalDTO $filters): PaginatedResult
    {
        $rentalFilterDomain = RentalFilter::create(
            $filters->startDateFrom,
            $filters->startDateTo,
            $filters->endDateFrom,
            $filters->endDateTo,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage,
            $filters->page,
        );

        return $this->repository->findByFilters($rentalFilterDomain);
    }
}
