<?php


namespace Modules\Laralite\Http\Requests;

class SaveSubscription extends AdminRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer',
            'default_credit_amount' => 'numeric',
            'default_initial_credit_amount' => 'numeric',
        ];
    }

    public function filters(): array
    {
        return [
            'price' => 'bcmul:100',
        ];
    }
}