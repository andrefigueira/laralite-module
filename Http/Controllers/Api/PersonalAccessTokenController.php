<?php


namespace Modules\Laralite\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenController extends Controller
{
    public function get(Request $request)
    {
        $tokens = PersonalAccessToken::query();

        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $tokens->get();
        }

        if ($request->input('filter') !== 'null') {
            $tokens->where('name', 'LIKE', '%' . $request->input('filter') . '%')->get();
        }

        if ($request->input('sortBy') !== null) {
            $tokens->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $tokens->paginate($perPage);
    }
}
