<?php

namespace Modules\Laralite\Http\Requests;

class AdminRequest extends FilterableRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::guard('api')->check();
    }
}
