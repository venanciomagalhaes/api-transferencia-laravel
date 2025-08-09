<?php

namespace App\Modules\User\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistsException extends AbstractBusinessException
{
    public function __construct(string $message = "This user already exists", int $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
