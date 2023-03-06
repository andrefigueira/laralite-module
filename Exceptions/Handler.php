<?php

namespace Modules\Laralite\Exceptions;

use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponses;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof MaintenanceModeException) {
            return redirect('/maintenance');
        }

        if ($request->getContentType() === 'json') {
            if ($e instanceof HttpRequestException) {
                return $this->error($e->getMessage(), $e->getResponseCode());
            }

            if ($e instanceof AppRuntimeException) {
                return $this->error($e->getMessage(), $e->getResponseCode());
            }
            if (!\Config::get('app.debug')) {
                $message = 'An unknown error has occurred';
                \Log::error($message . ' :' . $e->getMessage(), $e->getTrace());
                return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return parent::render($request, $e);
    }
}
