<?php

namespace App\Modules\Transaction\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class TransferAmountMustBeGreaterThanZeroException extends AbstractBusinessException
{
    public function __construct(string $message = "Transfer amount must be greater than zero", int $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
