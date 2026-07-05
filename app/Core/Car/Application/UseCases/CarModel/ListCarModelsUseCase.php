<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\CarModel;

use App\Core\Car\Application\DTOs\CarModel\FilterCarModelDTO;
use App\Core\Car\Domain\Collection\CarModelCollection;
use App\Core\Car\Domain\Queries\CarModelQueryFilter;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListCarModelsUseCase
{
    public function __construct(
        private CarModelRepositoryInterface $repository,
    ) {}

    /**
     * @return PaginatedResult<CarModelCollection>
     */
    public function execute(FilterCarModelDTO $filters): PaginatedResult
    {
        $carModelFilterDomain = CarModelQueryFilter::create(
            $filters->search,
            $filters->brandUuid,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage,
            $filters->page
        );

        return $this->repository->findByFilters($carModelFilterDomain);
    }
}
