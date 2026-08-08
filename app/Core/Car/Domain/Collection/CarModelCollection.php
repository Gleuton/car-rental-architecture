<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Collection;

use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use ArrayIterator;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @implements DomainCollectionInterface<CarModel>
 */
final class CarModelCollection implements DomainCollectionInterface, JsonSerializable
{
    /** @var list<CarModel> */
    private array $items = [];

    /**
     * @param list<CarModel> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateCarModel($item);
            $this->items[] = $item;
        }
    }

    public function add(mixed $item): self
    {
        $this->validateCarModel($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<CarModel>
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
     * @return list<CarModel>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    private function validateCarModel(mixed $carModel): void
    {
        if (! $carModel instanceof CarModel) {
            throw new InvalidArgumentException(
                sprintf('A CarModelCollection só aceita instâncias de %s.', CarModel::class)
            );
        }
    }
}
