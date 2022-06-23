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

    public function getDiscount(float $total): float
    {
        $type = $this->getAttributeValue('type');
        $value = $this->getAttributeValue('value');

        switch($type) {
            case 'percent':
                return ($total * $value) / 100;
            case 'fixed':
                return $value;
        }
    }
}
