<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Discount;
use Symfony\Component\HttpFoundation\Response;
use Log;

class DiscountController extends Controller
{
    public function get(Request $request)
    {
        $discounts = Discount::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $discounts->get();
        }

        if ($request->input('filter') !== 'null') {
            $discounts
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('type', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('value', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $discounts->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $discounts->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Discount::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get discount',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'value' => 'required',
        ]);

        try {
            $discount = Discount::create([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'value' => $request->get('value'),
            ]);

            Log::info('Created discount', [
                'request' => $request->all(),
                'discount' => $discount,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new discount',
                'data' => [
                    'discount' => $discount,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create discount', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create discount',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'value' => 'required',
        ]);

        try {
            $discount = Discount::where('id', '=', $id)->firstOrFail();

            $discount->update([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'value' => $request->get('value'),
            ]);

            Log::info('Updated discount', [
                'request' => $request->all(),
                'discount' => $discount,
            ]);

            return $discount;
        } catch (\Throwable $exception) {
            Log::error('Failed to update discount', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update discount',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $discount = Discount::where('id', '=', $id)->firstOrFail();

            $discount->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete discount',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
