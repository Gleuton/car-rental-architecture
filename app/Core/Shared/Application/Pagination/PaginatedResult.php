<?php

namespace App\Core\Shared\Application\Pagination;

class PaginatedResult
{
    /**
     * @template T
     * @param T $items
     */
    public function __construct(
        public mixed $items,
        public int $page,
        public int $perPage,
        public int $total,
        public int $lastPage,
    ) {}
}
