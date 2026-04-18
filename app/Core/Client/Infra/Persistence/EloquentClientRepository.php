<?php

declare(strict_types=1);

namespace App\Core\Client\Infra\Persistence;

use App\Core\Client\Domain\Entity\Client as DomainClient;
use App\Core\Client\Domain\Entity\ClientCollection;
use App\Core\Client\Domain\Entity\ClientFilter;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Client as EloquentClient;

class EloquentClientRepository implements ClientRepositoryInterface
{
    public function save(DomainClient $client): DomainClient
    {
        $model = EloquentClient::create([
            'uuid' => $client->uuid,
            'name' => $client->name,
        ]);

        return $this->toDomainClient($model);
    }

    /**
     * @return PaginatedResult<ClientCollection>
     */
    public function findByFilters(ClientFilter $filters): PaginatedResult
    {
        $paginator = EloquentClient::query()
            ->when(
                $filters->search,
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($filters->search).'%'])
            )
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage, ['*'], 'page', $filters->page);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            fn (EloquentClient $model) => $this->toDomainClient($model),
            static fn (array $items): ClientCollection => new ClientCollection($items)
        );
    }

    public function findByUuid(string $uuid): DomainClient
    {
        $model = EloquentClient::query()->where('uuid', $uuid)->firstOrFail();

        return $this->toDomainClient($model);
    }

    public function update(DomainClient $client): DomainClient
    {
        $model = EloquentClient::query()->where('uuid', $client->uuid)->firstOrFail();

        $model->update([
            'name' => $client->name,
        ]);

        return $this->toDomainClient($model);
    }

    public function deleteByUuid(string $uuid): void
    {
        $model = EloquentClient::query()->where('uuid', $uuid)->firstOrFail();
        $model->delete();
    }

    private function toDomainClient(EloquentClient $model): DomainClient
    {
        return DomainClient::restore(
            $model->name,
            $model->uuid,
        );
    }
}
