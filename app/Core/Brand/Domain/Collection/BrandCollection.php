<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Collection;

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Shared\Domain\Collection\DomainCollectionInterface;
use ArrayIterator;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @implements DomainCollectionInterface<Brand>
 */
final class BrandCollection implements DomainCollectionInterface, JsonSerializable
{
    /** @var list<Brand> */
    private array $items = [];

    /**
     * @param list<Brand> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateBrand($item);
            $this->items[] = $item;
        }
    }

    public function add(mixed $item): self
    {
        $this->validateBrand($item);
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<Brand>
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
     * @return list<Brand>
     */
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    private function validateBrand(mixed $brand): void
    {
        if (! $brand instanceof Brand) {
            throw new InvalidArgumentException(
                sprintf('A BrandCollection só aceita instâncias de %s.', Brand::class)
            );
        }
    }
}
