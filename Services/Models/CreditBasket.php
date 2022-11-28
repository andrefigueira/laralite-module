<?php

namespace Modules\Laralite\Services\Models;

use Modules\Laralite\Services\Models\Basket\Item;
use Modules\Laralite\Services\Models\Basket\Items;

class CreditBasket extends Model implements BasketInterface
{
    public function __construct(array $data)
    {
        $data['products'] = !empty($data['products']) ? new Items($data['products']) : new Items();
        $data['creditClaim'] = true;
        parent::__construct($data);
    }

    /**
     * @return Items|Item[]
     */
    public function getItems(): Items
    {
        return $this->data['products'];
    }

    public function getTotal(): int
    {
        return $this->data['total'] ?? 0;
    }

    public function setTotal(int $total): self
    {
        $this->data['total'] = $total;
        return $this;
    }
}