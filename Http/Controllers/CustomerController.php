<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Traits\ApiFailedValidation;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;


class CustomerController extends Controller
{
    use ApiResponses, ApiFailedValidation;

    public function account(Request $request): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        return $this->success([
            'user'  =>  [
                'id' => $customer->id,
                'name'  =>  $customer->name,
                'email' =>  $customer->email,
                'profile_image' => $customer->profile_image,
                'newsletter_subscription' => $customer->newsletter_subscription,
            ]
        ], '');
    }

    public function wallet(Request $request): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();

        $wallet = [];
        if ($customer->wallet()->count() > 0) {
            $wallet = $customer->wallet()->first()->toArray();
        }

        return $this->success($wallet, '');
    }

    public function orders(Request $request)
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        $orders = $customer->orders;
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $orders->get();
        }

        return response()->json($customer->orders()->orderBy('created_at', 'DESC')->paginate($perPage));
    }

    public function accountUpdate(AccountUpdateRequest $request)
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        $data = $request->only('name', 'email','profile_image', 'newsletter_subscription');

        $customer->update($data);

        return $this->success([
            'user'  =>  [
                'name'  =>  $customer->name,
                'email' =>  $customer->email,
                'profile_image' =>  $customer->profile_image,
                'newsletter_subscription' => $customer->newsletter_subscription,
            ]
        ], '');
    }

    public function changePassword(PasswordChangeRequest $request)
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        $data = $request->only('password');

        $customer->update([
            'password'  =>  \Hash::make($data['password'])
        ]);

        return $this->success([], 'Password updated successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function emailAvailable(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }

        $email = $request->get('email');
        $customer = Customer::where('email', '=' , $email)->whereNotNull('password')->first();
        $availability = $customer ? 'Unavailable' : 'Available';
        return $this->success(
            ['available' => !$customer],
            'Email is ' . $availability
        );
    }

    public function imageUpload(Request $request)
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $file = $request->file('file');

        $allowedImageTypes = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

        if (!in_array($file->getMimeType(), $allowedImageTypes, true)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Filetype (' . $file->getMimeType() . ') not allowed, must be of type ' . implode(', ', $allowedImageTypes),
            ], Response::HTTP_BAD_REQUEST);
        }

        $path = $file->storePublicly('public');

        return new JsonResponse([
            'success' => true,
            'message' => 'File Uploaded successfully',
            'data' => [
                'path' => Storage::url($path),
            ],
        ]);
    }

    public function orderDetails($id): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }
        $order = Order::where('unique_id', '=', $id)->with(['tickets', 'customer'])->get();

        if ($order === null) {
            throw new NotFoundHttpException('Order not found');
        }

        return $this->success([
            'order' => $order
        ], '');
    }

}
