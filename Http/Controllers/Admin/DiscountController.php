<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;

class DiscountController extends Controller
{
    use ApiResponses;

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
