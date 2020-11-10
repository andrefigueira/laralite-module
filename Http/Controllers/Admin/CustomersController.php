<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomersController extends Controller
{
    public function index()
    {
        return view('laralite::admin.customers');
    }

    public function view(string $uniqueId)
    {
        $customer = Customer::where('unique_id', '=', $uniqueId)->get();

        if ($customer === null) {
            throw new NotFoundHttpException('Customer not found');
        }

        return view('laralite::admin.customers.view', [
            'customer' => $customer,
        ]);
    }

    public function edit($id)
    {
        $customer = Customer::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.customers.form', [
            'type' => 'edit',
            'customer' => $customer,
        ]);
    }
}
