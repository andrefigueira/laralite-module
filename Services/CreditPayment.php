<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Exceptions\CreditPaymentException;
use Modules\Laralite\Models\CreditTransactions;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Customer\Wallet;
use Modules\Laralite\Models\Order;
use Throwable;

class CreditPayment
{

    public function __construct()
    {

    }

    /**
     * @throws CreditPaymentException|Throwable
     */
    public function processTransaction(Order $order): void
    {
        /** @var Customer $customer */
        $customer = $order->customer()->first();
        $amount = $order->basket->total;
        $wallet = $this->validateCustomerFunds($customer, $amount);

        /** @var CreditTransactions $creditTransaction */
        $creditTransaction = CreditTransactions::create(
            [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
            ]
        );

        $wallet->balance -= $creditTransaction->amount;
        $wallet->saveOrFail();
    }

    /**
     * @throws CreditPaymentException
     */
    private function validateCustomerFunds($customer, $credit): Wallet
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::where('customer_id', $customer->id)->first();
        if (!$wallet || $wallet->balance < $credit) {
            throw CreditPaymentException::insufficientCredits(
                'You do not have enough credit to make this purchase'
            );
        }

        return $wallet;
    }
}