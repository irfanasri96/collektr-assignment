<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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

    public function render($request, Throwable $exception)  
    {
        // Check if there is an active transaction
        if (DB::transactionLevel() > 0) {
            DB::rollback();
        }
        
        if ($exception instanceof HttpException) {
            $message = [
                'message' => $exception->getMessage(),
                'status' => false,
            ];

            return response()->json($message, $exception->getStatusCode());
        } elseif ($exception instanceof ValidationException) {
            $message = [
                'status' => false,
                'errors' => $exception->validator->errors(),
            ];

            return response()->json($message, Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } elseif ($exception instanceof RouteNotFoundException || $exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            $message = [
                'status' => false,
                'message' => 'Not Found.',
            ];

            return response()->json($message, Response::HTTP_NOT_FOUND);

        } elseif ($exception instanceof AuthenticationException) {
            $message = [
                'status' => false,
                'message' => 'Unauthenticated.',
            ];
            
            return response()->json($message, Response::HTTP_UNAUTHORIZED);

        } elseif ($exception instanceof QueryException) {
            $message = [
                'status' => false,
                'message' => 'Bad Request.',
            ];

            return response()->json($message, Response::HTTP_BAD_REQUEST);

        } elseif ($exception instanceof Throwable) {
            $message = [
                'status' => false,
                'message' => 'Internal Server Error.',
            ];

            return response()->json($message, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}
