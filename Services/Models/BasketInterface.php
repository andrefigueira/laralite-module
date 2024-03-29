<?php

namespace Modules\Laralite\Services\Models;

interface BasketInterface
{
    public function getItems();
    public function getTotal();
    public function getItemsTotal();
    public function setTotal(int $total);
}