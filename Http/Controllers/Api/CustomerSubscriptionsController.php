<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Exceptions\HttpRequestException;
use Modules\Laralite\Exceptions\PaymentException;
use Modules\Laralite\Models\Customer\Subscription as CustomerSubscription;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Services\CustomerSubscriptionService;
use Modules\Laralite\Services\PaymentService;
use Modules\Laralite\Traits\ApiResponses;

class CustomerSubscriptionsController extends Controller
{
    use ApiResponses;

    private PaymentService $paymentService;
    private CustomerSubscriptionService $customerSubscriptionService;

    public function __construct(PaymentService $paymentService, CustomerSubscriptionService $customerSubscriptionService)
    {
        $this->paymentService = $paymentService;
        $this->customerSubscriptionService = $customerSubscriptionService;
    }

    public function get(Request $request): LengthAwarePaginator
    {
        $subscribers = CustomerSubscription::query();
        $perPage = $request->get('perPage', 10);
        $sortBy = $request->input('sortBy');

        if ($subscriptionId = $request->get('subscriptionId')) {
            $subscribers->where('subscription_id', '=', $subscriptionId);
        }

        if ($customerId = $request->get('customer_id')) {
            $subscribers->where('customer_id', '=', $customerId);
        }

        if ($request->input('sortBy') !== null) {
            $subscribers->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $subscribers->with(['subscription', 'customer', 'subscriptionPrice'])->paginate($perPage);
    }

    public function getPayments(int $id, Request $request): LengthAwarePaginator
    {
        $perPage = $request->get('perPage', 10);
        $payments = Payment::query();
        return $payments->where('payable_type', '=', CustomerSubscription::class)
            ->where('payable_id', '=', $id)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * @throws HttpRequestException
     * @throws PaymentException
     */
    public function refundSubscriptionPayment(int $id, int $payId, Request $request): JsonResponse
    {
        $reason = $request->post('reason');
        $cancelSubscription = $request->post('cancelSubscription');
        $payment = Payment::where('payable_id', '=', $id)
            ->where('payable_type', '=', CustomerSubscription::class)
            ->findOrFail($payId);
        $data = [
            'reason' => $reason,
        ];
        $this->paymentService->refundPayment($payment, $data);

        if ($cancelSubscription) {
            $this->customerSubscriptionService->cancel($payment->payable()->first());
        }

        return $this->success($payment->toArray(), 'Refund was Successful');
    }
}
