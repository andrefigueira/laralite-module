<?php

namespace Modules\Laralite\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Modules\Laralite\Http\Requests\AdminRequest;
use Modules\Laralite\Traits\ApiFailedAuthorisation;
use Modules\Laralite\Traits\ApiFailedValidation;

class DiscountSave extends AdminRequest
{
    use ApiFailedValidation, ApiFailedAuthorisation;

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
            'name' => 'required|max:255',
            'code' => 'required|max:255|alpha_num',
            'type' => [
                'required',
                Rule::in(['fixed', 'percent']),
            ],
            'value' => 'required|integer' . ($this->isPercentageDiscount() ? '|max:100' : ''),
            'end_date' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'value.max' => 'discount percentage cannot exceed 100%',
        ];
    }

    public function filters(): array
    {
        $filter = $this;
        return [
            'value' => [
                $this->getConditionRule(function () use ($filter) {
                    $request = $filter->getInputSource()->all();
                    $type = $request['type'] ?? null;
                    return $type === 'fixed';
                }),
                'bcmul:100',
                'intval',
            ],
        ];
    }

    public function isPercentageDiscount(): bool
    {
        $type = $this->getInputSource()->all()['type'] ?? '';
        return 'percent' === $type;
    }
}
