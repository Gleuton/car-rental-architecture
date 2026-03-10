<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Entity;

use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use ArrayIterator;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @implements DomainCollectionInterface<Rental>
 */
final class RentalCollection implements DomainCollectionInterface, JsonSerializable
{
    /** @var list<Rental> */
    private array $items = [];

    /**
     * @param list<Rental> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateRental($item);
            $this->items[] = $item;
        }
    }

    public function add(mixed $item): self
    {
        $this->validateRental($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<Rental>
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

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return list<Rental>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    private function validateRental(mixed $rental): void
    {
        if (! $rental instanceof Rental) {
            throw new InvalidArgumentException(
                sprintf('A RentalCollection so aceita instancias de %s.', Rental::class)
            );
        }
    }
}
