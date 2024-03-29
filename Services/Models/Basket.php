<?php

namespace Modules\Laralite\Services\Models;

use Modules\Laralite\Services\Models\Basket\Discountable;
use Modules\Laralite\Services\Models\Basket\Discounts;
use Modules\Laralite\Services\Models\Basket\FeeAble;
use Modules\Laralite\Services\Models\Basket\Item;
use Modules\Laralite\Services\Models\Basket\Items;
use Modules\Laralite\Services\Models\Basket\Taxable;

class Basket extends Model implements BasketInterface, FeeAble, Discountable, Taxable
{
    protected array $data = [
        'serviceFee' => 0,
        'total' => 0,
        'discounts' => null,
        'products' => null,
        'discountAmount' => 0,
        'taxAmount' => 0,
    ];

    public function __construct(array $data)
    {
        $data['discounts'] = !empty($data['discounts']) ? new Discounts($data['discounts']) : new Discounts();
        $data['products'] = !empty($data['products']) ? new Items($data['products']) : new Items();
        parent::__construct($data);
    }

    /**
     * @return Items|Item[]
     */
    public function getItems(): Items
    {
        return $this->data['products'];
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return isset($this->data['total']) ? (int)$this->data['total'] : 0;
    }

    public function setTotal(int $total): Basket
    {
        $this->data['total'] = $total;
        return $this;
    }

    /**
     * @return Discounts
     */
    public function getDiscounts(): Discounts
    {
        return $this->data['discounts'] ?? new Discounts();
    }

    /**
     * @return int
     */
    public function getDiscountAmount(): int
    {
        return isset($this->data['discountAmount']) ? (int)$this->data['discountAmount'] : 0;
    }

    public function setDiscountAmount(int $amount): Basket
    {
        $this->data['discountAmount'] = $amount;
        $this->data['total'] -= $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getTaxAmount(): int
    {
        return isset($this->data['taxAmount']) ? (int)$this->data['taxAmount'] : 0;
    }

    public function setTaxAmount(int $amount): Basket
    {
        $this->data['taxAmount'] = $amount;
        $this->data['total'] += $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getServiceFee(): int
    {
        return isset($this->data['serviceFee']) ? (int)$this->data['serviceFee'] : 0;
    }

    public function setServiceFee(int $fee): Basket
    {
        $this->data['serviceFee'] = $fee;
        $this->data['total'] += $fee;
        return $this;
    }

    private function getSubtotal(): int
    {
        $subtotal = 0;
        foreach ($this->getItems() as $item) {
            $subtotal += ($item->getPrice() * $item->getQuantity());
        }

        return $subtotal;
    }


    public function getDiscountedItemsTotal(): int
    {
        return $this->getSubtotal() - $this->getDiscountAmount();
    }

    public function getItemsTotal()
    {
        $itemsTotal = 0;
        foreach ($this->getItems() as $item) {
            $itemsTotal += ($item->getPrice() * $item->getQuantity());
        }

        return $itemsTotal;
    }
}