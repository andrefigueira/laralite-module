<?php

namespace Modules\Laralite\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait ApiFailedAuthorisation
{
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Unauthorised',
            'data'      => []
        ], Response::HTTP_UNAUTHORIZED));
    }
}