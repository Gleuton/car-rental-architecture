<?php

namespace App\Core\Brand\Domain\Entity;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @extends Collection<int, Brand>
 */
class BrandCollection extends Collection
{
    /**
     * @param  array<int, Brand>  $items
     */
    public function __construct($items = [])
    {
        foreach ($items as $item) {
            $this->validateBrand($item);
        }

        parent::__construct($items);
    }

    private function validateBrand(mixed $brand): void
    {
        if (!$brand instanceof Brand) {
            throw new InvalidArgumentException(
                sprintf('A BrandCollection só aceita instâncias de %s.', Brand::class)
            );
        }
    }

    public function offsetSet($key, $value): void
    {
        $this->validateBrand($value);
        parent::offsetSet($key, $value);
    }

    /**
     * @param  mixed  ...$values
     * @return BrandCollection
     */
    public function push(...$values): self
    {
        foreach ($values as $value) {
            $this->validateBrand($value);
        }

        return parent::push(...$values);
    }

    /**
     * @param $value
     * @param ?int $key
     * @return BrandCollection
     */
    public function prepend($value, $key = null): self
    {
        $this->validateBrand($value);

        return parent::prepend($value, $key);
    }

    /**
     * @param  Brand  $item
     */
    public function add($item): self
    {
        $this->validateBrand($item);

        return parent::add($item);
    }
}
