<?php

namespace Modules\Laralite\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class AppRuntimeException extends \RuntimeException
{
    protected int $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    public function setResponseCode(string $responseCode): void
    {
        $this->responseCode = $responseCode;
    }
}