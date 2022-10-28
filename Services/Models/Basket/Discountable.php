<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\BasketInterface;

interface Discountable extends BasketInterface
{
    public function getDiscounts();

    public function getDiscountAmount();
}