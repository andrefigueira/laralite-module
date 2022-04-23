<?php


namespace Modules\Laralite\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Laralite\Models\Subscription;

class SubscriptionsController
{
    public function get(Request $request)
    {
        $subscriptions = Subscription::query();
        $perPage = $request->get('perPage', 10);

        if ($request->get('all') === 'true') {
            return $subscriptions->get();
        }

        if ($request->input('filter') !== 'null') {
            $subscriptions
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $subscriptions->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $subscriptions->with('prices')->paginate($perPage);
    }
}