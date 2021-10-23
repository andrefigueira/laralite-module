<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Traits\ApiFailedValidation;
use Modules\Laralite\Traits\ApiResponses;


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
                'name'  =>  $customer->name,
                'email' =>  $customer->email,
                'newsletter_subscription' => $customer->newsletter_subscription,
            ]
        ], '');
    }

    public function orders(): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        return $this->success([
            'orders'    =>  $customer->orders,
        ], '');
    }

    public function accountUpdate(AccountUpdateRequest $request)
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        $customer = auth('customers')->user();

        $data = $request->only('name', 'email', 'newsletter_subscription');

        $customer->update($data);

        return $this->success([
            'user'  =>  [
                'name'  =>  $customer->name,
                'email' =>  $customer->email,
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
}
