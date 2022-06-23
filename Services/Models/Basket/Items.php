<?php

namespace Modules\Laralite\Services\Models\Basket;

use ArrayAccess;
use Modules\Laralite\Services\Models\Collection;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Services\Models\CollectionInterface;

class Items extends Collection
{
    /**
     * @var ArrayAccess|Discount[]
     */
    protected ArrayAccess $collection;

    public function __construct(array $items = [])
    {
        foreach($items as &$item) {
            $item = new Item($item);
        }
        parent::__construct($items);
    }

    /**
     * @param $value
     * @return Items
     */
    public function add($value): CollectionInterface
    {
        if ($value instanceof ItemInterface) {
            throw new \InvalidArgumentException('`$value` parameter must be of ' . Discount::class . ' type',);
        }
        return parent::add($value);
    }
}