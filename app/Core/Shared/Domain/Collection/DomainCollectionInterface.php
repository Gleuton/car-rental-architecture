<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Collection;

use Countable;
use IteratorAggregate;

/**
 * @template T
 * @extends IteratorAggregate<int, T>
 */
interface DomainCollectionInterface extends Countable, IteratorAggregate
{
    /**
     * @param T $item
     * @return DomainCollectionInterface<T>
     */
    public function add(mixed $item): DomainCollectionInterface;

    /**
     * @return list<T>
     */
    public function all(): array;

    public function isEmpty(): bool;
}