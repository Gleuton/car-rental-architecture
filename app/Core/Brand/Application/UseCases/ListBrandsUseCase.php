<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Infra\Mappers\EloquentBrandMapper;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;

readonly class ListBrandsUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
    ) {}

    public function execute(FilterBrandDTO $filters): PaginatedResult
    {
        $brandFilterDomain = BrandFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage
        );

        $paginator = $this->repository->findByFilters($brandFilterDomain);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            [EloquentBrandMapper::class, 'toDomain']
        );
    }
}
