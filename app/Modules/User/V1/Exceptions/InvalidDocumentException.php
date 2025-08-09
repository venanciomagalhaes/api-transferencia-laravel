<?php

namespace App\Modules\User\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class InvalidDocumentException extends AbstractBusinessException
{
    public function __construct(string $message = "This document is invalid", int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        parent::__construct($message, $code);
    }
}
