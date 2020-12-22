<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function get(Request $request)
    {
        $customers = Customer::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $customers->get();
        }

        if ($request->input('filter') !== 'null') {
            $customers
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $customers->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $customers->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Customer::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get customer',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
