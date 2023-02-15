<?php

namespace Modules\Laralite\Constant;

use Illuminate\Support\Facades\Redis;

class ProductType extends AbstractConstant
{
    public const STANDARD = 1;
    public const RESERVATION = 2;
}