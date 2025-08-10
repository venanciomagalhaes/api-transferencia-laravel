<?php

namespace App\Modules\Transaction\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class PayerAndPayeeAreTheSameUserException extends AbstractBusinessException
{
    public function __construct(string $message = 'The payer and payee are the same user', int $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
