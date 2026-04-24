<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Collection;

use App\Core\Car\Domain\Entities\Car;
use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use ArrayIterator;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @implements DomainCollectionInterface<Car>
 */
final class CarCollection implements DomainCollectionInterface, JsonSerializable
{
    /** @var list<Car> */
    private array $items = [];

    /**
     * @param list<Car> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateCar($item);
            $this->items[] = $item;
        }
    }

    public function add(mixed $item): self
    {
        $this->validateCar($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<Car>
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
     * @return list<Car>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    private function validateCar(mixed $car): void
    {
        if (! $car instanceof Car) {
            throw new InvalidArgumentException(
                sprintf('A CarCollection só aceita instâncias de %s.', Car::class)
            );
        }
    }
}
