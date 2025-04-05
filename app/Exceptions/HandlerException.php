<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class HandlerException extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception): Response|JsonResponse
    {
        if ($request->expectsJson()) {
            if ($exception instanceof BusinessException) {
                return response()->json(
                    ['message' => $exception->getMessage()],
                    $exception->getCode()
                );
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Recurso não encontrado.',
                ], Response::HTTP_NOT_FOUND);
            }
        }

        return parent::render($request, $exception);
    }
}
