<?php

namespace Modules\Laralite\Filters;

use \Closure;

class Condition
{
    private Closure $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function filterIf(): bool
    {
        return ($this->callback)();
    }
}