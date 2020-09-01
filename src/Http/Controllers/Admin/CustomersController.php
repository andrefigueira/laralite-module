<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\CradleMoney\Models\Customers;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomersController extends Controller
{
    public function index()
    {
        return view('laralite::admin.customers');
    }

    public function view(string $uniqueId)
    {
        $customer = Customers::where('unique_id', '=', $uniqueId)->get();

        if ($customer === null) {
            throw new NotFoundHttpException('Customer not found');
        }

        return view('laralite::admin.customers.view', [
            'customer' => $customer,
        ]);
    }

    public function edit($id)
    {
        $customer = Customers::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.customers.form', [
            'type' => 'edit',
            'customer' => $customer,
        ]);
    }
}
