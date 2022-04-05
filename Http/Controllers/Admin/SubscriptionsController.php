<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Subscriptions;

class SubscriptionsController extends Controller
{
    public function index()
    {
        return view('laralite::admin.subscriptions');
    }

    public function create()
    {
        return view('laralite::admin.subscriptions.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $subscription = Subscriptions::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.subscriptions.form', [
            'type' => 'edit',
            'subscription' => $subscription,
        ]);
    }
}
