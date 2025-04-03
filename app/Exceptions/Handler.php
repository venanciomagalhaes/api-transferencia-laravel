<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    //melhorar o tratamento de erros
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        if ($request->expectsJson()) {
            if ($exception instanceof BusinessException) {
                return response()->json(
                    ['message' => $exception->getMessage()],
                    $exception->getCode()
                );
            }
        }

        return parent::render($request, $exception);
    }
}
