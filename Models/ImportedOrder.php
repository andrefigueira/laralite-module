<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ImportedOrder
 * @package Modules\Laralite\Models
 * @mixin Eloquent
 */
class ImportedOrder extends Model
{
    protected $fillable = ['order_id', 'ext_order_id'];
}