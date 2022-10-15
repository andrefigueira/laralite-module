<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Illuminate\Http\Request;

trait SessionPaymentTrait
{
    /**
     * @param Request $request
     * @param array $data
     */
    private function setSessionPaymentData(Request $request, array $data): void
    {
        $request->session()->put('paymentSession', $data);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSessionPaymentData(Request $request): array
    {
        return $request->session()->get('paymentSession', []);
    }
}