<?php

namespace App\Modules\Transaction\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedTransferException extends AbstractBusinessException
{
    public function __construct(string $message = 'This payer is not authorized to perform this transfer.', int $code = Response::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message, $code);
    }
}
