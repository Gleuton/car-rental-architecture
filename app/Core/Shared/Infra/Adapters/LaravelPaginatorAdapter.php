<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Adapters;

use App\Core\Shared\Application\Pagination\PaginatedResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @template T
 */
final class LaravelPaginatorAdapter
{
    /**
     * @template TIn
     * @template TOut
     *
     * @param callable(TIn):TOut $mapper
     * @param (callable(array<int, TOut>): mixed)|null $collectionFactory
     *
     * @return PaginatedResult<TOut>
     */
    public static function adapt(
        LengthAwarePaginator $paginator,
        callable $mapper,
        ?callable $collectionFactory = null
    ): PaginatedResult {
        $items = array_map($mapper, $paginator->items());

        return new PaginatedResult(
            items: $collectionFactory ? $collectionFactory($items) : $items,
            page: $paginator->currentPage(),
            perPage: $paginator->perPage(),
            total: $paginator->total(),
            lastPage: $paginator->lastPage(),
        );
    }
}
