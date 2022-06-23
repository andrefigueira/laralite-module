<?php

namespace Modules\Laralite\Services\Models;

use ArrayObject;

abstract class Collection implements CollectionInterface
{
    protected \ArrayAccess $collection;

    /**
     * @var \ArrayIterator
     */
    private $iterator;

    public function __construct(array $data = [])
    {
        $this->collection = new ArrayObject($data, ArrayObject::ARRAY_AS_PROPS);
        $this->iterator = $this->collection->getIterator();
    }

    public function getIterator(): \IteratorAggregate
    {
        return $this->collection;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    public function offsetSet($offset, $value): Collection
    {
        if ($offset === null) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }

        return $this;
    }

    public function add($value): CollectionInterface
    {
        $this->offsetSet(null, $value);
        return $this;
    }

    public function arrayColumn(string $column): array
    {
        return array_column($this->collection->getArrayCopy(), $column);
    }

    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function get(int $key)
    {
        return $this->offsetGet($key);
    }

    public function remove($key): CollectionInterface
    {
        $this->offsetUnset($key);
        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->collection->getArrayCopy();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = $this->collection->getArrayCopy();

        foreach ($data as $key => $item) {
            if ($item instanceof Model || $item instanceof Collection) {
                $data[$key] = $item->toArray();
            }
        }

        return $data;
    }
}