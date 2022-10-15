<?php

namespace Modules\Laralite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'paymentMethod' => Rule::requiredIf(function () {
                return !$this->request->get('paymentIntent');
            }),
            'paymentIntent' => Rule::requiredIf(function () {
                return !$this->request->get('paymentMethod');
            }),
            'price_id' => 'exists:subscription_prices,id',
        ];
    }
}
