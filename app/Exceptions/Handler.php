<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\MethodNotAllowedHttpException;
use App\Exceptions\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
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
     * @return \Illuminate\Http\JsonResponse
     */
    // public function render($request, Throwable $exception)
    // {
    //     // Handle validation exceptions
    //     if ($exception instanceof ValidationException) {
    //         return response()->json([
    //             'error' => 'Validation error',
    //             'messages' => $exception->errors(),
    //         ], 422);
    //     }

    //     // Handle model not found (404)
    //     if ($exception instanceof ModelNotFoundException) {
    //         return response()->json([
    //             'error' => 'Resource not found',
    //             'message' => 'The requested resource could not be found.',
    //         ], 404);
    //     }

    //     // Handle route not found (404)
    //     if ($exception instanceof NotFoundHttpException) {
    //         return response()->json([
    //             'error' => 'Not found',
    //             'message' => 'The specified route could not be found.',
    //         ], 404);
    //     }

    //     // Handle method not allowed (405)
    //     if ($exception instanceof MethodNotAllowedHttpException) {
    //         return response()->json([
    //             'error' => 'Method not allowed',
    //             'message' => 'The HTTP method used is not allowed for this endpoint.',
    //         ], 405);
    //     }

    //     // Handle unauthorized access (401)
    //     if ($exception instanceof HttpException && $exception->getStatusCode() === 401) {
    //         return response()->json([
    //             'error' => 'Unauthorized',
    //             'message' => 'You are not authorized to access this resource.',
    //         ], 401);
    //     }

    //     // Handle other HTTP exceptions (fallback for unexpected errors)
    //     if ($exception instanceof HttpException) {
    //         return response()->json([
    //             'error' => 'HTTP error',
    //             'message' => $exception->getMessage(),
    //         ], $exception->getStatusCode());
    //     }

    //     // Default error handler for unexpected exceptions
    //     return response()->json([
    //         'error' => 'Server error',
    //         'message' => 'An unexpected error occurred. Please try again later.',
    //     ], 500);
    // }

    public function render($request, Throwable $exception)
    {
        // Handle validation exceptions (422)
        if ($exception instanceof ValidationException) {
            return $exception->render();
        }

        // Handle model not found (404)
        if ($exception instanceof ModelNotFoundException) {
            return $exception->render();
        }

        // Handle method not allowed (405)
        if ($exception instanceof MethodNotAllowedException) {
            return $exception->render();
        }

        // Handle unauthorized access (401)
        if ($exception instanceof UnauthorizedAccessException) {
            return $exception->render();
        }

        // Handle route not found (404)
        if ($exception instanceof RouteNotFoundException) {
            return $exception->render();
        }

        // Handle other HTTP exceptions (generic)
        if ($exception instanceof HttpException) {
            return $exception->render();
        }
        if($exception instanceof DublicatedFileNameException){
            return $exception->render();
        }
        // Default error handler for unexpected exceptions (500)
        return response()->json([
            'error' => 'Server error',
            'message' => 'An unexpected error occurred. Please try again later.',
        ], 500);
    }
}
