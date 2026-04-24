<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Collection\BrandCollection;
use App\Core\Brand\Domain\Query\BrandQueryFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListBrandsUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
    ) {}

    /**
     * @return PaginatedResult<BrandCollection>
     */
    public function execute(FilterBrandDTO $filters): PaginatedResult
    {
        $brandQueryFilter = BrandQueryFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage,
            $filters->page
        );

        return $this->repository->findByFilters($brandQueryFilter);
    }
}
