<?php

declare(strict_types=1);

namespace App\Core\Client\Application\UseCases;

use App\Core\Client\Application\DTOs\FilterClientDTO;
use App\Core\Client\Domain\Entity\ClientCollection;
use App\Core\Client\Domain\Entity\ClientFilter;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListClientsUseCase
{
    public function __construct(
        private ClientRepositoryInterface $repository,
    ) {}

    /**
     * @return PaginatedResult<ClientCollection>
     */
    public function execute(FilterClientDTO $filters): PaginatedResult
    {
        $clientFilterDomain = ClientFilter::create(
            $filters->search,
            $filters->orderBy,
            $filters->direction,
            $filters->perPage,
            $filters->page
        );

        return $this->repository->findByFilters($clientFilterDomain);
    }
}
