<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Laralite\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('laralite::admin.product');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('laralite::admin.product.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $product = Product::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.product.form', [
            'type' => 'edit',
            'product' => $product,
        ]);
    }
}
