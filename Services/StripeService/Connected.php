<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

/**
 * Trait Connected
 * @package Modules\Laralite\Services\StripeService
 * @property StripeClient client
 */
trait Connected
{
    /**
     * @param array $paymentIntent
     * @param array $payload
     * @return ApiResourceWrapper|void
     * @throws ApiErrorException
     */
    public function updateConnectedPayment(array $paymentIntent, array $payload)
    {
        $search['transfer_group'] = $paymentIntent['transfer_group'] ?? null;
        $search['destination'] = $paymentIntent['transfer_data']['destination'] ?? null;
        if ($search['destination'] === null) {
            \Log::alert('No connected account transfer was found for payment: ' . json_encode($paymentIntent));
            return;
        }
        $charges = $this->searchTransfers($search);
        $chargeId = $charges->toArray()['data']['0']['destination_payment'] ?? null;
        if ($payload['metadata']['description']) {
            $payload['description'] = $payload['metadata']['description'];
        }

        return $this->getApiResourceWrapper(
            $this->client->charges->update(
                $chargeId,
                $payload,
                [
                    'stripe_account' => $search['destination']
                ]
            )
        );
    }
}