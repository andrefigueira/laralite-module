<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\Model;

class Item extends Model implements ItemInterface
{
    public function getPrice(): int
    {
        return (int)$this->data['price'];
    }

    public function setPrice(int $price)
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
        return (int)$this->offsetGet('credit');
    }

    public function getQuantity(): int
    {
        return (int)$this->offsetGet('quantity');
    }
}