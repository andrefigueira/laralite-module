<?php

namespace Modules\Laralite\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpRequestException extends AppException
{
    protected int $responseCode = Response::HTTP_BAD_REQUEST;

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}