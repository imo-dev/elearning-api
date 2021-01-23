<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof NotFoundHttpException){
            return apiResponse(
                [],
                'not found',
                false,
                'error.404',
                null,
                404
            );
        } else if($exception instanceof ModelNotFoundException){
            $search = explode('[App\\Models\\', $exception->getMessage());
            $search = explode(']', $search[1]);
            $model_name = strtolower($search[0]);
            return apiResponse(
                [],
                'not found',
                false,
                'error.404',
                [ $model_name => [$exception->getMessage()] ],
                404
            );
        } 
        // else if ($exception instanceof) {
        //     $customMeta = [];
        //     $debug = config('app.debug', false);
        //     if ($debug == true) $customMeta['exception'] = [
        //         'message' => $exception->getMessage(),
        //         'file' => $exception->getFile(),
        //         'line' => $exception->getLine(),
        //         'trace' => $exception->getTrace()
        //     ];
        //     return apiResponse(
        //         [],
        //         'server error',
        //         false,
        //         'error.500',
        //         null,
        //         500,
        //         $customMeta
        //     );
        // }

        return parent::render($request, $exception);
    }
}
