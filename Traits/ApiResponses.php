<?php

namespace Modules\Laralite\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Modules\Laralite\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    protected function success(array $data, ?string $message = null, $code = 200, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $code);
    }

    protected function error(string $message, int $code, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function unknownError(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'An unknown error has occurred!',
        ], 500);
    }

    /**
     * @param Exception $e
     * @return JsonResponse
     */
    protected function handleCaughtException(Exception $e): JsonResponse
    {
        \Log::error($e->getMessage(), $e->getTrace());

        if ($e instanceof AppException) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->unknownError();
    }
}
