<?php

namespace App\Core\Brand\Application\UseCases;

use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class ListBrandsUseCase
{
    public function __construct(
        private BrandRepositoryInterface $repository,
    ) {}

    public function execute(FilterBrandDTO $filters): LengthAwarePaginator
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
