<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Repositories;

use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Entity\ClientCollection;
use App\Core\Client\Domain\Entity\ClientFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;

interface ClientRepositoryInterface
{
    public function save(Client $client): Client;

    /**
     * @return PaginatedResult<ClientCollection>
     */
    public function findByFilters(ClientFilter $filters): PaginatedResult;

    public function findByUuid(string $uuid): Client;

    public function update(Client $client): Client;

    public function deleteByUuid(string $uuid): void;
}
