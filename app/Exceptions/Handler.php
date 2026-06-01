<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // API requests always get JSON — even if AjaxHeader middleware hasn't run yet
        // (e.g. exceptions thrown in EnsureFrontendRequestsAreStateful before our middleware).
        $isApiRequest = str_starts_with($request->getPathInfo(), '/api/');

        if (!config('app.debug') && !$this->isHttpException($exception)) {
            if ($isApiRequest || $request->expectsJson()) {
                \Log::error('API exception: ' . $exception->getMessage(), [
                    'exception' => get_class($exception),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'url' => $request->fullUrl(),
                ]);
                return response()->json(['message' => 'Server Error'], 500);
            }
            // Non-API, non-JSON: render blade 500 so a secondary exception can't blank the page.
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $exception);
    }
}
