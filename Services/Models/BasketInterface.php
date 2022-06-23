<?php

namespace Modules\Laralite\Services\Models;

interface BasketInterface
{
    public function getItems();

    public function getTotal();

    public function getDiscounts();

    public function getDiscountAmount();

    public function getTaxAmount();

    public function getServiceFee();
}