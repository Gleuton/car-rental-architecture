<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Entity;

use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use ArrayIterator;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @implements DomainCollectionInterface<Client>
 */
final class ClientCollection implements DomainCollectionInterface, JsonSerializable
{
    /** @var list<Client> */
    private array $items = [];

    /**
     * @param list<Client> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateClient($item);
            $this->items[] = $item;
        }
    }

    public function add(mixed $item): self
    {
        $this->validateClient($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<Client>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return ArrayIterator<int, Client>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return list<Client>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    private function validateClient(mixed $item): void
    {
        if (! $item instanceof Client) {
            throw new InvalidArgumentException('Invalid client');
        }
    }
}
