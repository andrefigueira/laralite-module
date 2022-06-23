<?php

namespace Modules\Laralite\Services\Models;

use Modules\Laralite\Services\Models\Basket\Discounts;
use Modules\Laralite\Services\Models\Basket\Items;

class Basket extends Model implements BasketInterface
{
    public function __construct(array $data)
    {
        $data['discounts'] = !empty($data['discounts']) ? new Discounts($data['discounts']) : new Discounts();
        $data['products'] = !empty($data['products']) ? new Items($data['products']) : new Items();
        parent::__construct($data);
    }

    /**
     * @return Item[]|Items
     */
    public function getItems(): Items
    {
        return $this->data['products'];
    }

    /**
     * @param Items $items
     * @return $this
     */
    public function setItems(Items $items): Basket
    {
        $this->data['products'] = $items;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): float
    {
        return isset($this->data['total']) ? (float)$this->data['total'] : 0;
    }

    public function setTotal(float $total): Basket
    {
        $this->data['total'] = $total;
        return $this;
    }

    /**
     * @return Discounts
     */
    public function getDiscounts(): Discounts
    {
        return isset($this->data['discounts']) ? $this->data['discounts'] : new Discounts();
    }

    public function setDiscounts(Discounts $discounts): Basket
    {
        $this->data['discounts'] = $discounts;
        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountAmount(): float
    {
        return isset($this->data['discountAmount']) ? (float)$this->data['discountAmount'] : 0.00;
    }

    public function setDiscountAmount(float $amount): Basket
    {
        $this->data['discountAmount'] = $amount;
        $this->data['total'] -= $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getTaxAmount(): float
    {
        return isset($this->data['taxAmount']) ? (float)$this->data['taxAmount'] : 0;
    }

    public function setTaxAmount(float $amount): Basket
    {
        $this->data['taxAmount'] = $amount;
        $this->data['total'] += $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getServiceFee(): float
    {
        return isset($this->data['serviceFee']) ? (int)$this->data['serviceFee'] : 0;
    }

    public function setServiceFee(float $serviceFee): Basket
    {
        $this->data['serviceFee'] = $serviceFee;
        $this->data['total'] += $serviceFee;
        return $this;
    }
}