<?php

namespace Modules\Laralite\Services\Models\Basket;

use ArrayAccess;
use InvalidArgumentException;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Services\Models\Collection;

class Discounts extends Collection
{
    /**
     * @var ArrayAccess|Discount[]
     */
    protected ArrayAccess $collection;

    public function __construct(array $discounts = [])
    {
        foreach($discounts as &$discount) {
            if ($discount instanceof Discount) {
                continue;
            }
            $discount = new Discount($discount);
        }
        parent::__construct($discounts);
    }

    /**
     * @param Discount $value
     * @return Discounts
     */
    public function add($value): Discounts
    {
        if (!$value instanceof Discount) {
            throw new InvalidArgumentException('`$value` parameter must be of ' . Discount::class . ' type',);
        }
        parent::add($value);
    }
}