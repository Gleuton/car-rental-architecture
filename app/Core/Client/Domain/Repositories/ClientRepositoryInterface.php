<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Repositories;

use App\Core\Client\Domain\Entity\ClientCollection;
use App\Core\Client\Domain\Entity\ClientFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface ClientRepositoryInterface
{
    /**
     * @return PaginatedResult<ClientCollection>
     */
    public function findByFilters(ClientFilter $filters): PaginatedResult;
}
