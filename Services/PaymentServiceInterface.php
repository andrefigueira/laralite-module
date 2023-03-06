<?php

namespace Modules\Laralite\Services;

interface PaymentServiceInterface
{
    public function createPayment($paymentOptions, $basket);
}