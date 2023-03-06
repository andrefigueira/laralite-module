<?php

namespace Modules\Laralite\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Mail;
use Modules\Laralite\Exceptions\AppException;
use Modules\Laralite\Exceptions\UnexpectedPaymentGatewayException;
use Modules\Laralite\Mail\SubscriptionConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Models\Subscription\Price;
use Ramsey\Uuid\Uuid;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class CustomerSubscriptionService
{
    protected SettingsService $settingsService;
    protected StripeService $stripeService;

    public function __construct(SettingsService $settingsService, StripeService $stripeService)
    {
        $this->settingsService = $settingsService;
        $this->stripeService = $stripeService;
    }

    /**
     * @param Customer $customer
     * @param int $priceId
     * @param Payment $payment
     * @param Discount|null $discount
     * @return Customer\Subscription
     * @throws Exception
     */
    public function saveSubscription(
        Customer  $customer,
        int       $priceId,
        Payment   $payment,
        ?Discount $discount = null
    ): Customer\Subscription
    {
        /** @var Price $price */
        $price = Price::findOrFail($priceId);
        /** @var Subscription $subscription */
        $subscription = $price->subscription()->first();
        /** @var Customer\Subscription $customerSubscription */
        $customerSubscription = $customer->subscriptions()->firstOrCreate([
            'price_id' => $price->id,
            'subscription_id' => $subscription->id,
        ], [
            'unique_id' => Uuid::uuid4()->toString(),
        ]);

        $payment->setPayableType(Customer\Subscription::class);
        $payment->payable_id = $customerSubscription->id;
        $payment->save();
        if ($creditAmount = $subscription->getAttributeValue('default_initial_credit_amount')) {
            $this->creditCustomerWallet($customer, $creditAmount);
        }

        $customerSubscription->status = 'ACTIVE';
        $customerSubscription->agreed_price = $price->price;
        $customerSubscription->start_date = new \DateTime();
        $customerSubscription->expiry_date = new \DateTime('+ 1' . $price->recurring_period);
        $customerSubscription->setStripePaymentMethodDetails($payment->getStripePaymentMethodDetails());
        $customerSubscription->setStripePaymentIntentId($payment->getStripePaymentIntentId());
        $customerSubscription->setStripePaymentMethodId($payment->getStripePaymentMethodId());
        $customerSubscription->save();
        $this->sendSubscriptionNotification($customer, $customerSubscription, $subscription, $discount);

        return $customerSubscription;
    }

    /**
     * @param Customer\Subscription $subscription
     * @throws UnexpectedPaymentGatewayException|AppException
     * @throws Exception
     */
    public function collectionSubscriptionPayment(Customer\Subscription $subscription): void
    {
        /** @var Customer $customer */
        $customer = $subscription->customer()->first();
        /** @var Price $plan */
        $price = $subscription->subscriptionPrice()->first();
        $currency = $this->settingsService->getCurrency();

        if (!$agreedPrice = $subscription->getAttributeValue('agreed_price')) {
            $m = 'Subscription `' . $subscription->id . '`  agreed price not set when it should be.';
            \Log::alert($m);
        }

        try {
            $payment_intent = $this->stripeService->createPaymentIntent([
                'amount' => $agreedPrice ?? $price->price,
                'currency' => strtolower($currency['value']),
                'customer' => $customer->getStripeCustomerId(),
                'payment_method' => $subscription->getStripePaymentMethodId(),
                'off_session' => true,
                'confirm' => true,
            ]);
        } catch (ApiErrorException $e) {
            \Log::error('Failed to charge subscription fee with error: ' . $e->getMessage(), $e->getTrace());
            throw new UnexpectedPaymentGatewayException(
                'Failed to charge subscription fee with error: ' . $e->getMessage(),
                $e->getCode(),
                $e->getPrevious()
            );
        } catch (\Throwable $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            throw new AppException('An unknown error has occurred', '', $e);
        }

        if ($payment_intent->paymentCompleted()) {
            $subscription->expiry_date = new \DateTime('+ 1' . $price->recurring_period);
            $subscription->status = 'ACTIVE';
            $subscription->save();
        } else {
            $subscription->status = $this->getSubscriptionStatusByPaymentStatus($payment_intent->get('status'));
            $subscription->save();
        }

        \Log::info('Subscription Charged and updated successfully', [
            'payment_intent' => $payment_intent->toArray(),
            'subscription' => $subscription->toArray(),
            'price' => $price->toArray(),
        ]);
    }

    private function getSubscriptionStatusByPaymentStatus(string $status): string
    {
        switch ($status) {
            case PaymentIntent::STATUS_PROCESSING:
                return Customer\Subscription::STATUS_PAYMENT_DUE;
            case PaymentIntent::STATUS_REQUIRES_ACTION:
            case PaymentIntent::STATUS_REQUIRES_CAPTURE:
            case PaymentIntent::STATUS_REQUIRES_PAYMENT_METHOD:
                return Customer\Subscription::STATUS_PAYMENT_DECLINED;
            default:
                return Customer\Subscription::STATUS_PENDING_PAYMENT;
        }
    }

    /**
     * @param Subscription|Model|int $subscription
     */
    public function cancel($subscription): void
    {
        if (!$subscription instanceof Customer\Subscription) {
            $subscription = Subscription::firstOrFail($subscription);
        }
        $subscription->status = Subscription::STATUS_CANCELED;
        $subscription->save();
    }

    private function creditCustomerWallet(Customer $customer, $creditAmount): void
    {
        $customerWallet = $customer->wallet()->first();
        if ($customerWallet) {
            $customerWallet->balance += $creditAmount;
            $customerWallet->save();
        } else {
            $customer->wallet()->create([
                'balance' => $creditAmount
            ]);
        }
    }

    private function sendSubscriptionNotification(
        Customer              $customer,
        Customer\Subscription $customerSubscription,
        Subscription          $subscription,
        Discount              $discount = null
    ): void
    {
        try {
            Mail::to($customer->email)->send(new SubscriptionConfirmation([
                'customer' => $customer,
                'subscription' => $customerSubscription,
                'subscriptionPlan' => $subscription,
                'discount' => $discount,
            ]));
        } catch (Exception $e) {
            $k = $e;
        }
    }
}