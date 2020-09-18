<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Laralite\Models\ProductCategory;

class ProductCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('laralite::admin.product-category');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('laralite::admin.product-category.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.product-category.form', [
            'type' => 'edit',
            'productCategory' => $productCategory,
        ]);
    }
}
