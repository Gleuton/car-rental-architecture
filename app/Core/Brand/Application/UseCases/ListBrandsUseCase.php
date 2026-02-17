<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListBrandsUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
    ) {
    }

    /**
     * @param FilterBrandDTO $filters
     * @return PaginatedResult<Brand>
     */
    public function execute(FilterBrandDTO $filters): PaginatedResult
    {
        $brandFilterDomain = BrandFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage
        );

        return $this->repository->findByFilters($brandFilterDomain);
    }
}
