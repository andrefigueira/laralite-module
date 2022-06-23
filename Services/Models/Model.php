<?php

namespace Modules\Laralite\Services\Models;

class Model implements \ArrayAccess, \JsonSerializable
{
    /**
     * @var array
     */
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = $this->data;

        foreach ($data as $key => $item) {
            if ($item instanceof Model || $item instanceof Collection) {
                $data[$key] = $item->toArray();
            }
        }

        return $data;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
