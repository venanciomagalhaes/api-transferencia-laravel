<?php

namespace App\Modules\Common\V1\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AppExceptionHandler extends Handler
{
    public function render($request, Throwable $e): Response
    {

        if ($e instanceof AbstractBusinessException) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode()
            );
        }

        return parent::render($request, $e);
    }
}
