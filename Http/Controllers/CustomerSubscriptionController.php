<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiFailedValidation;
use Modules\Laralite\Traits\ApiResponses;


class CustomerSubscriptionController extends Controller
{
    use ApiResponses, ApiFailedValidation;

    /**
     * @var StripeService
     */
    private $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function get(): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();

        return $this->success($customer
            ->subscriptions()
            ->with('subscription')
            ->whereIn('status', ['ACTIVE', 'CANCELED'])
            ->getResults()
            ->all());
    }

    public function cancel($id): JsonResponse
    {
        if(!auth('customers')->id()) {
            return $this->error('You are not authorized to access this', 403);
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();

        try {
            /** @var Customer\Subscription $subscription */
            $subscription = $customer->subscriptions()->findOrFail($id);
        } catch (\Throwable $e) {
            \Log::error($e);
            return $this->error('Unable to find subscription with ID ' . $id, 404);
        }

        $subscription->status = 'CANCELED';
        $subscription->expiry_date = null;

        try {
            $stripeSubscription = $this->stripeService->getSubscription($subscription->getStripeSubscriptionId());
            $stripeSubscription->deleteSubscription();
        } catch (\Throwable $e) {
            \Log::error('Failed to cancel subscription on Stripe', $e->getTrace());
            return $this->error('Failed to cancel subscription' . $id, 500);
        }

        $subscription->setStripeSubscriptionId(null);
        $subscription->setStripeSubscriptionEndPeriod(null);
        $subscription->setStripePaymentIntentId(null);
        $subscription->save();

        return $this->success($subscription->toArray(), 'Subscription cancelled successfully');
    }
}
