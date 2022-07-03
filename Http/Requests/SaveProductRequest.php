<?php


namespace Modules\Laralite\Http\Requests;

class SaveProductRequest extends AdminRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'slug' => 'required',
        ];
    }

    public function filters(): array
    {
        return [
            'variants.*.pricing.price' => ['bcmul:100', 'intval'],
            'variants.*.pricing.sale_price' => ['bcmul:100', 'intval']
        ];
    }
}