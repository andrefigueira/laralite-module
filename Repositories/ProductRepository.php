<?php

namespace Modules\Laralite\Repositories;

use Modules\Laralite\Models\Product;

class ProductRepository
{
    public function find($id): ?Product
    {
        return Product::find($id);
    }

    public function firstWhere($where)
    {
        return Product::firstWhere($where);
    }


}