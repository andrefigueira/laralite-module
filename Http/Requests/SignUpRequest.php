<?php

namespace Modules\Laralite\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Laralite\Traits\ApiFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->where(function ($query) {
                    return $query->whereNotNull('password');
                })
            ],
            'name' => 'required|max:255',
            'password' => 'required|min:8|max:20',
            'password_confirm' =>  'same:password',
            'newsletter_subscription' => 'array:email,sms,phone',
        ];
    }
}
