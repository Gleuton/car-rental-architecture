<?php

namespace App\Core\Shared\Infra\Adapters;

use App\Models\Brand as EloquentBrand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Core\Shared\Application\Pagination\PaginatedResult;

final class LaravelPaginatorAdapter
{
    /**
     * @template TIn
     * @template TOut
     *
     * @param LengthAwarePaginator<int, EloquentBrand> $paginator
     * @param callable(TIn):TOut $mapper
     * @return PaginatedResult
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
