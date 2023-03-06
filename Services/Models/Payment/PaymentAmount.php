<?php

namespace Modules\Laralite\Services\Models\Payment;

use Modules\Laralite\Services\Models\Model;

class PaymentAmount extends Model
{
    protected array $data = [
        'total' => 0,
        'subtotal' => 0,
        'applyFees' => true,
    ];

    public function getTotal(): int
    {
        return $this->data['total'] ?? 0;
    }

    public function getSubTotal(): int
    {
        return $this->data['subtotal'] ?? 0;
    }

    public function applyFees(): bool
    {
        return $this->data['applyFees'] ?? false;
    }
}