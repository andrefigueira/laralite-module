<?php

namespace Modules\Laralite\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;


class ClientController extends Controller
{
    public function get(Request $request)
    {
        $clients = Client::query();

        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $clients->get();
        }

        if ($request->input('filter') !== 'null') {
            $clients->where('id', 'LIKE', '%' . $request->input('filter') . '%')
                    ->orWhere('name', 'LIKE', '%' . $request->input('filter') . '%')
                    ->orWhere('secret', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $clients->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        /*return $clients->paginate($perPage);*/
        return response()->json($clients->orderBy('created_at', 'DESC')->paginate($perPage));
    }
}
