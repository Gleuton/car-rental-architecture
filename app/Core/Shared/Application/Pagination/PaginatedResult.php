<?php

declare(strict_types=1);

namespace App\Core\Shared\Application\Pagination;

/**
 * @template T
 */
class PaginatedResult
{
    /**
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
