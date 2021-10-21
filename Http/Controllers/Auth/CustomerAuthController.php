<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Http\Requests\SignUpRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Auth;


class CustomerAuthController extends Controller
{
    use ApiResponses;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!auth('customers')->attempt($data)) {
            return $this->error('Invalid email and password Please try again', 401);
        }

        return $this->success([], 'Login Successful', '200');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth('customers')->logout();

        return redirect('/');
    }

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