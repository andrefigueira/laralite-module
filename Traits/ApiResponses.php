<?php

namespace Modules\Laralite\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function success(?array $data, ?string $message = null, $code = 200): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message, int $code, array $errors = []): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function unknownError(): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => 'An unknown error has occurred!',
        ], 500);
    }
}
