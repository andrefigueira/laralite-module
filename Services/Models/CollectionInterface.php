<?php

namespace Modules\Laralite\Services\Models;

interface CollectionInterface extends \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable
{
    public function add($value): CollectionInterface;

    public function arrayColumn(string $column): array;

    public function get(int $key);

    public function remove(int $key);
}