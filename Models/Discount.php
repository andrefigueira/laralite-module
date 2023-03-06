<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 * @package Modules\Laralite\Models
 * @mixin Eloquent
 */
class Discount extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'end_date',
        'start_date',
    ];

    public const CUSTOMER_HIDDEN_ATTRIBUTES = [
        'updated_at',
        'created_at',
        'end_date',
        'start_date',
        'id',
        'name'
    ];

    public function getDiscount(int $total): int
    {
        $type = $this->getType();
        $value = $this->getValue();

        switch($type) {
            case 'percent':
                return ($total * $value) / 100;
            case 'fixed':
                return $value;
        }
    }

    public function isOneHundredPercentDiscount(): bool
    {
        $type = $this->getType();
        $value = $this->getValue();

        return ($type === 'percent' && $value >= 100);
    }

    public function getType(): ?string
    {
        return $this->getAttributeValue('type');
    }

    public function getValue(): ?string
    {
        return $this->getAttributeValue('value');
    }
}
