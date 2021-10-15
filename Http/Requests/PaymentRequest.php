<?php

namespace Modules\Laralite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
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
            'customer.email' => 'required|email',
            'customer.name' => 'required|max:255',
            'customer.password' => 'nullable|min:8|max:20',
            'customer.password_confirm' => [
                Rule::requiredIf(function () {
                    return (!empty($this->input('customer.password')));
                }),
                'same:customer.password'
            ],
            'customer.newsletter_subscription' => 'array:email,sms,phone'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

//    public function prepareForValidation(): void
//    {
//        $this->merge([
//            'customer.newsletter_subscription' => is_string($this->input('customer.newsletter_subscription'))
//                ? json_decode($this->input('customer.newsletter_subscription')) : [],
//        ]);
//    }
}
