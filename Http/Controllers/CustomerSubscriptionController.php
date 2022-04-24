<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Jobs\Stripe\SubscriptionDelete;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiFailedValidation;
use Modules\Laralite\Traits\ApiResponses;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use TheSeer\Tokenizer\Exception;


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
            ->with(['subscriptionPrice', 'subscriptionPrice.subscription'])
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

//        if ($stripeSubscriptionId = $subscription->getStripeSubscriptionId()) {
//            try {
//                throw new InvalidRequestException('sdsd');
//                $stripeSubscription = $this->stripeService->getSubscription($stripeSubscriptionId);
//                $stripeSubscription->deleteSubscription();
//            } catch (ApiErrorException $e) {
//                \Log::error('Failed to cancel subscription ID `' . $subscription->id .
//                    '` with Stripe Subscription ID `' . $stripeSubscriptionId . '` with error: ' . $e->getMessage(),
//                    $e->getTrace()
//                );
//
//                if ($e->getHttpStatus() === 404) {
//                    \Log::error('Stripe subscription ID `' . $stripeSubscriptionId . '` no longer exists: ',
//                        $e->getTrace()
//                    );
//                } else {
//                    //Add job to queue to have subscription deletion from stripe attempted again at a later time
//                    SubscriptionDelete::dispatchAfterResponse(
//                        $subscription->getStripeSubscriptionId(),
//                        ['subscriptionId' => $subscription]
//                    );
//                }
//            } catch (\Throwable $e) {
//                \Log::error($e->getMessage(), $e->getTrace());
//            }
//        } else {
//            \Log::alert('Subscription ID `' . $subscription->id .
//                '` has no stripe ID set. and is unable to cancel stripe subscription!'
//            );
//        }
//
//        $subscription->setStripeSubscriptionId(null);
//        $subscription->setStripeSubscriptionEndPeriod(null);
        $subscription->setStripePaymentIntentId(null);
        $subscription->save();

        return $this->success($subscription->toArray(), 'Subscription cancelled successfully');
    }
}
