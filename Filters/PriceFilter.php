<?php

namespace Modules\Laralite\Filters;

class PriceConverter implements FilterInterface
{
    /**
     * @param mixed $value
     * @return string
     */
    public function filter($value): string
    {
        return bcmul($value, 100);
    }
}