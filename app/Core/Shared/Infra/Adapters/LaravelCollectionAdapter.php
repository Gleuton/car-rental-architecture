<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Adapters;

use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use Illuminate\Support\Collection;
use Traversable;

/**
 * @template T
 *
 * @implements DomainCollectionInterface<T>
 */
class LaravelCollectionAdapter implements DomainCollectionInterface
{
    /**
     * @var Collection<int, T>
     */
    private Collection $collection;

    /**
     * @param iterable<int, T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->collection = new Collection($items);
    }

    /**
     * @param T $item
     *
     * @return DomainCollectionInterface<T>
     */
    public function add(mixed $item): DomainCollectionInterface
    {
        $this->collection->push($item);

        return $this;
    }

    /**
     * @return list<T>
     */
    public function all(): array
    {
        return $this->collection->values()->all();
    }

    public function isEmpty(): bool
    {
        return $this->collection->isEmpty();
    }

    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * @return Traversable<int, T>
     */
    public function getIterator(): Traversable
    {
        return $this->collection->getIterator();
    }
}
