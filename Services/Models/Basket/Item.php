<?php

namespace Modules\Laralite\Services\Models\Basket;

use Modules\Laralite\Services\Models\Model;

class Item extends Model implements ItemInterface
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function getPrice(): int
    {
        return (int)$this->data['price'];
    }

    public function setPrice(int $price): Item
    {
        $this->offsetSet('price', $price);

        return $this;
    }

    public function getId(): int
    {
        return (int)$this->offsetGet('id');
    }

    public function getSku(): string
    {
        return (string)$this->offsetGet('sku');
    }

    public function getCreditPrice(): int
    {
        return (int)$this->offsetGet('credits');
    }

    public function setCreditPrice(int $creditAmount): Item
    {
        $this->offsetSet('credits', $creditAmount);

        return $this;
    }

    public function getQuantity(): int
    {
        return (int)$this->offsetGet('quantity') ?: 1;
    }
}