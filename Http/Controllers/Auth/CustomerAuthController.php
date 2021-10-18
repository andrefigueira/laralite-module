<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\LoginRequest;
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
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            /** @var Customer $customer */
            $customer = Customer::firstOrNew([ 'email' => $data['email']]);
            $data['unique_id'] =  Uuid::uuid4();
            $data['password'] = \Hash::make($data['password']);
            $customer->fill($data);
            $customer->save();
        } catch(\Throwable $e) {
            \Log::error('User Signup failure', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Unknown Error Has Occurred',
                'realError' => $e->getMessage(),
                'data' => $request->all()
            ], 500);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Signup Successful',
            'data' => $customer->toArray()
        ], '200');
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
}
