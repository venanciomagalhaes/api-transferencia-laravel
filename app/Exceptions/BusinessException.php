<?php

namespace App\Exceptions;

use Exception;

abstract class BusinessException extends Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code, null);
    }
}
