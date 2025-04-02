<?php

use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if(request()->expectsJson()){
            $exceptions->render(function (BusinessException $exception){
                return response()->json(['error' => $exception->getMessage()], $exception->getCode());
            });
            $exceptions->render(function (Exception $exception){
                return response()->json(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
            });
        }
    })->create();
