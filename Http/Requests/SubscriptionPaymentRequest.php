<?php

namespace Modules\Laralite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Laralite\Traits\ApiFailedValidation;

class SubscriptionPaymentRequest extends FormRequest
{
    use ApiFailedValidation;

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'price_id' => 'exists:subscription_prices,id',
        ];
    }
}
