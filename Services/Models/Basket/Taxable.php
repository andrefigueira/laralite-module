<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\BasketInterface;

interface Taxable extends BasketInterface
{
    public function getTaxAmount();
}