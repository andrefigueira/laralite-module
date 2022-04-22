<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Laralite\Http\Requests\SignUpRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Services\StripeService;
use Ramsey\Uuid\Uuid;

class SignupController extends Controller
{
    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * Create a new controller instance.
     *
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
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

            if (!$customer->getStripeCustomerId()) {
                $stripeCustomer = $this->stripeService->saveCustomer([
                    'name' => $customer->name,
                    'email' => $customer->email,
                ]);
                $customer->setStripeCustomerId($stripeCustomer->get('id'));
                $customer->save();
            }
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
}