<?php

namespace App\Exceptions;

use App\Classes\Response as HTTPResponse;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $statusCode = $exception->getStatusCode();

        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['error' => 'Not Found!'], 404);
        } elseif ($exception instanceof GuzzleException) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        } elseif ($statusCode === 422) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        } else {
            return response()->json(['error' => "Something went wrong, Try later", 'status' => 500], 500);
        }
    }
}
