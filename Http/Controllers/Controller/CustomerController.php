<?php

namespace Modules\Laralite\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Traits\ApiResponses;


class CustomerController extends Controller
{
    use ApiResponses;

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
}
