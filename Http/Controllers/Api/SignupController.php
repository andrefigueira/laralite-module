<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Laralite\Http\Requests\SignUpRequest;
use Modules\Laralite\Models\Customer;
use Ramsey\Uuid\Uuid;

class SignupController extends Controller
{
    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['unique_id'] =  Uuid::uuid4();
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
                'data' => $request->all()
            ], 500);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Signup Successful',
            'data' => $customer->toArray()
        ], '200');
    }
}
