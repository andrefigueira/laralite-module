<?php

namespace Modules\Laralite\Http\Requests;

interface FilterableInterface
{
    public function filters(): array;

    public function filterData();

    public function getFilteredData();
}