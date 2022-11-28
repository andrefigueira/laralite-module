<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\BasketInterface;

interface FeeAble extends BasketInterface
{
    public function getServiceFee();
}