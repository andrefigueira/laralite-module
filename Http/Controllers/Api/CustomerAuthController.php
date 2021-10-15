<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Http\Requests\SignUpRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Auth;


class CustomerAuthController extends Controller
{
    use ApiResponses;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!auth('customers')->attempt($data)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        /** @var Customer $customer */
        $customer = Customer::where('email', $data['email'])->first();
        auth('customers')->login($customer);

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
            $data['unique_id'] =  Uuid::uuid4();
            $data['password'] = \Hash::make($data['password']);
            $customer = new Customer($data);
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
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
