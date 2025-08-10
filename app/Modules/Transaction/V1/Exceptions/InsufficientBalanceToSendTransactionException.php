<?php

namespace App\Modules\Transaction\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class InsufficientBalanceToSendTransactionException extends AbstractBusinessException
{
    public function __construct(
        string $message = 'The payer does not have enough balance to perform this transaction.',
        int $code = Response::HTTP_BAD_REQUEST
    ) {
        parent::__construct($message, $code);
    }
}
