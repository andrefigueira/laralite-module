<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Customer\Subscription as CustomerSubscription;

class CustomerSubscriptionsController extends Controller
{
    public function index()
    {
        return view('laralite::admin.subscribers');
    }

    public function view(int $id)
    {
        $customerSubscription = CustomerSubscription::with(
            'customer',
            'subscription',
            'subscriptionPrice',
            'payments'
        )->findOrFail($id);
        return view('laralite::admin.subscribers.view', [ 'customerSubscription' => $customerSubscription ]);
    }
}
