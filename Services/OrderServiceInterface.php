<?php

namespace Modules\Laralite\Services;

use Illuminate\Database\Eloquent\Model;

interface OrderServiceInterface
{
    public function saveOrder(array $orderData): Model;

    /**
     * @param Model| string | int $order
     * @param array $updateArray
     * @return mixed
     */
    public function update($order, array $updateArray);
}