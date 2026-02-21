<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\CarModel\Application\DTOs\FilterCarModelDTO;
use App\Core\CarModel\Domain\Entity\CarModelCollection;
use App\Core\CarModel\Domain\Entity\CarModelFilter;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
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
        $carModelFilterDomain = CarModelFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage,
            $filters->page
        );

        return $this->repository->findByFilters($carModelFilterDomain);
    }
}
