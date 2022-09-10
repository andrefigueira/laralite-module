<?php

namespace Modules\Laralite\Services\Models;

use Modules\Laralite\Services\Models\Basket\Discounts;
use Modules\Laralite\Services\Models\Basket\Item;
use Modules\Laralite\Services\Models\Basket\Items;

class Basket extends Model implements BasketInterface
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

    public function setServiceFee(int $serviceFee): Basket
    {
        $this->data['serviceFee'] = $serviceFee;
        $this->data['total'] += $serviceFee;
        return $this;
    }

    public function getSubtotal(): int
    {
        $total = $this->getItemsTotal();

        return $total - $this->getDiscountAmount();
    }

    public function getTotalCredit()
    {
        $total = $this->getItemsTotal(true);

        return $total - $this->getDiscountAmount();
    }

    private function getItemsTotal(bool $inCredits = false)
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $price = $inCredits ? $item->getCreditPrice() : $item->getPrice();
            $total += $price * $item->getQuantity();
        }

        return $total;
    }


}