<?php

namespace Modules\Laralite\Exceptions;

class CreditPaymentException extends HttpRequestException
{
    public static function insufficientCredits(string $error): CreditPaymentException
    {
        return new static('Insufficient Credits: ' . $error);
    }
}