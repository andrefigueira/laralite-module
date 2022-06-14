<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\StripeClient;

/**
 * Trait Connected
 * @package Modules\Laralite\Services\StripeService
 * @property StripeClient client
 */
trait Connected
{
    public function updateConnectedPayment(array $paymentIntent, array $payload): ApiResourceWrapper
    {
        $search['transfer_group'] = $paymentIntent['transfer_group'] ?? null;
        $search['destination'] = $paymentIntent['transfer_data']['destination'] ?? null;
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