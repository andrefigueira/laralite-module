<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\Api\DiscountSave;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use Log;

class DiscountController extends Controller
{
    use ApiResponses;

    /*public function __construct()
    {
        $this->middleware('auth:admin')->except('verify');
    }*/

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
                ->where('code', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('type', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('value', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $discounts->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $discounts->paginate($perPage);
    }

    /**
     * @param $code
     * @return Builder|Model|JsonResponse|Discount
     */
    public function verify($code)
    {
        try {
            return Discount::where('code', '=', $code)
                ->where(function ($query) {
                    $query->whereDate('end_date', '>=', Carbon::now())
                        ->orWhereNull('end_date');
                })
                ->firstOrFail()
                ->makeHidden(Discount::CUSTOMER_HIDDEN_ATTRIBUTES);
        } catch (ModelNotFoundException $exception) {
            return $this->error(
                'Failed to verify discount',
                Response::HTTP_NOT_FOUND,
                [
                    $exception->getMessage(),
                ]
            );
        } catch (\Throwable $exception) {
            return $this->error(
                'Failed to verify discount',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [
                    $exception->getMessage(),
                ]
            );
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getOne($id): JsonResponse
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

    /**
     * @param DiscountSave $request
     * @return JsonResponse
     */
    public function create(DiscountSave $request): JsonResponse
    {
        try {
            $discount = Discount::create($request->validated());

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

            return $this->error(
                'Failed to create discount',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param DiscountSave $request
     * @param $id
     * @return JsonResponse
     */
    public function update(DiscountSave $request, $id): JsonResponse
    {
        try {
            $discount = Discount::where('id', '=', $id)->firstOrFail();

            $discount->update($request->validated());

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

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
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
