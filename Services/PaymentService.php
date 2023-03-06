<?php

namespace Modules\Laralite\Services;

use JsonException;
use Modules\Laralite\Exceptions\HttpRequestException;
use Modules\Laralite\Exceptions\PaymentException;
use Modules\Laralite\Exceptions\UnexpectedPaymentGatewayException;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Services\Models\Payment\PaymentAmount;
use Psy\Util\Json;
use stdClass;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;

class PaymentService
{
    private StripeService $stripeService;

    private SettingsService $settingsService;

    public function __construct(StripeService $stripeService, SettingsService $settingsService)
    {
        $this->stripeService = $stripeService;
        $this->settingsService = $settingsService;
    }

    /**
     * @param string $paymentMethodId
     * @param PaymentAmount $paymentAmount
     * @return Payment
     * @throws ApiErrorException
     */
    public function createPayment(
        string $paymentMethodId,
        PaymentAmount $paymentAmount
    ): Payment
    {
        $amount = $paymentAmount->getTotal();
        $feeCollection = $this->settingsService->isFeeCollectionActive();
        $currencySettings = $this->settingsService->getCurrency() ?: [];
        $feeCollectionAmount = null;
        if ($paymentAmount->applyFees()) {
            $feeCollectionAmount = $feeCollection
                ? (int)(round(($feeCollection['feeAmount'] / 100) * $paymentAmount->getSubtotal()))
                + $this->settingsService->getServiceFeeAmount()
                : 0;
        }
        $payment = Payment::create();


        if ($feeCollectionAmount) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $amount,
                'currency' => strtolower($currencySettings['value']),
                'confirmation_method' => 'manual',
                'application_fee_amount' => $feeCollectionAmount,
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'metadata' => [
                    'payment_id' => $payment->id,
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId'],
                'confirm' => true,
            ]);
        } else {
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $amount,
                'currency' => strtolower($currencySettings['value']),
                'confirmation_method' => 'manual',
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'metadata' => [
                    'payment_id' => $payment->id,
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId'],
                'confirm' => true,
            ]);
        }

        $payment->payment_processor_result = $intent->toArray();
        $payment->ext_id = $intent->get('id');
        $payment->status = Payment::STATUS_COMPLETE;

        if ($intent->get('status') === 'requires_action' && $intent->get('next_action/type') === 'use_stripe_sdk') {
            $payment->status = Payment::STATUS_3DS_AUTHENTICATION_REQUIRED;
        }

        $payment->setStripePaymentIntentId($intent->get('id'));
        $payment->setStripePaymentMethodId($paymentMethodId);
        $payment->setStripePaymentMethodDetails($this->getPaymentMethodDetails($paymentMethodId));
        $payment->save();

        $payment->actionLog()->create([
            'action_type' => Payment\ActionLog::ACTION_TYPE_CREATE,
            'result_status' => Payment\ActionLog::RESULT_STATUS_SUCCEED,
            'payment_processor_result' => $payment->payment_processor_result,
            'meta_data' => '[]',
        ]);

        return $payment;
    }

    private function getPaymentMethodDetails($paymentMethodId): array
    {
        $details = [];
        try {
            $details = $this->stripeService->getPaymentMethod($paymentMethodId)->getPaymentMethodDetails();
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to get payment method details from stripe for Payment with error: '
                . $e->getMessage(),
                $e->getTrace()
            );
        }
        return $details;
    }

    /**
     * @throws ApiErrorException
     */
    public function confirmPayment(Payment $payment): void
    {
        $intent = $this->stripeService->confirmPaymentIntent($payment->ext_id);
        $payment->status = Payment::STATUS_COMPLETE;
        $payment->save();

        $payment->actionLog()->create([
            'action_type' => Payment\ActionLog::ACTION_TYPE_CONFIRM,
            'result_status' => Payment\ActionLog::RESULT_STATUS_SUCCEED,
            'payment_processor_result' => $intent->toArray(),
            'meta_data' => '[]',
        ]);
    }

    /**
     * @throws PaymentException|HttpRequestException
     * @throws UnexpectedPaymentGatewayException
     */
    public function refundPayment(Payment $payment, array $data = []): void
    {
        if (Payment::STATUS_COMPLETE !== $payment->status) {
            throw new PaymentException(
                'Cannot process refund for payment in status `' . $payment->status . '`'
            );
        }

        $data['reverse_transfer'] = true;

        try {
            $refund = $this->stripeService->refund($payment->ext_id, $data);
        } catch (ApiErrorException $e) {
            \Log::error('Unexpected payment gateway error: ' . $e->getMessage(), $e->getTrace());
            throw new UnexpectedPaymentGatewayException(
                'An unexpected error has occurred during refund request.');
        }

        switch ($refund->get('status')) {
            case Refund::STATUS_FAILED:
                \Log::alert('Failed refund attempt', $refund->toArray());
                $payment->actionLog()->create([
                    'action_type' => Payment\ActionLog::ACTION_TYPE_REFUND,
                    'result_status' => Payment\ActionLog::RESULT_STATUS_FAILED,
                    'payment_processor_result' => $refund->toArray(),
                    'meta_data' => '[]',
                ]);
                break;
            case Refund::STATUS_SUCCEEDED:
                $payment->status = Payment::STATUS_REFUNDED;


                $payment->actionLog()->create([
                    'action_type' => Payment\ActionLog::ACTION_TYPE_REFUND,
                    'result_status' => Payment\ActionLog::RESULT_STATUS_SUCCEED,
                    'payment_processor_result' => $refund->toArray(),
                    'meta_data' => '[]',
                ]);
                break;
            case Refund::STATUS_PENDING:
            case Refund::STATUS_CANCELED:
                //TODO need to handle these scenario maybe using stripe hooks endpoint to listen for status update
                break;
            default:
                // Unknown status
                break;
        }
        $payment->save();
    }

    /**
     * @param stdClass|array $paymentResults
     * @return Payment
     * @throws JsonException
     */
    public function createPaymentFromResults($paymentResults): Payment
    {
        $paymentResults = !$paymentResults instanceof stdClass ? json_decode(
            json_encode($paymentResults, JSON_THROW_ON_ERROR),
            false,
            512, JSON_THROW_ON_ERROR
        ) : $paymentResults;
        $payment = Payment::newModelInstance();
        $payment->ext_id = $paymentResults->id;
        $payment->status = Payment::STATUS_COMPLETE;
        $payment->setStripePaymentIntentId($paymentResults->id);
        $payment->setStripePaymentMethodDetails($this->getPaymentMethodDetails($paymentResults->payment_method));
        $payment->save();

        return $payment;
    }
}