<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Laralite\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('laralite::admin.discount');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('laralite::admin.discount.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $discount = Discount::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.discount.form', [
            'type' => 'edit',
            'discount' => $discount,
        ]);
    }
}
