<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Adapters;

use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Models\Brand as EloquentBrand;
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
     * @param LengthAwarePaginator<int, EloquentBrand> $paginator
     * @param callable(TIn):TOut $mapper
     *
     * @return PaginatedResult<TOut>
     */
    public static function adapt(
        LengthAwarePaginator $paginator,
        callable $mapper
    ): PaginatedResult {
        $items = array_map($mapper, $paginator->items());

        return new PaginatedResult(
            items: $items,
            page: $paginator->currentPage(),
            perPage: $paginator->perPage(),
            total: $paginator->total(),
            lastPage: $paginator->lastPage(),
        );
    }
}
