<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Infra\Mappers\EloquentBrandMapper;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Brand as EloquentBrand;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class ListBrandsUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
    ) {}

    /**
     * @param FilterBrandDTO $filters
     * @return PaginatedResult
     */

    public function execute(FilterBrandDTO $filters): PaginatedResult
    {
        $brandFilterDomain = BrandFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage
        );

        $paginator =  $this->repository->findByFilters($brandFilterDomain);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            [EloquentBrandMapper::class, 'toDomain']
        );
    }
}
