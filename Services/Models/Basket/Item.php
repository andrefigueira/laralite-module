<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\Model;

class Item extends Model implements ItemInterface
{
    public function getPrice(): float
    {
        return (float)$this->data['price'];
    }

    public function setPrice(float $price)
    {
        $this->offsetSet('price', $price);
    }

    public function getId(): int
    {
        return (int)$this->offsetGet('id');
    }

    public function getSku(): string
    {
        return (string)$this->offsetGet('sku');
    }

    public function getCreditPrice()
    {
        return (float)$this->offsetGet('credit');
    }

    public function getQuantity(): int
    {
        return (int)$this->offsetGet('quantity');
    }
}