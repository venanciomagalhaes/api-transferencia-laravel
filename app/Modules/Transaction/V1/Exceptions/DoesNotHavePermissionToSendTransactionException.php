<?php

namespace App\Modules\Transaction\V1\Exceptions;

use App\Modules\Common\V1\Exceptions\AbstractBusinessException;
use Symfony\Component\HttpFoundation\Response;

class DoesNotHavePermissionToSendTransactionException extends AbstractBusinessException
{
    public function __construct(
        string $message = 'This user type does not have permission to send transactions.',
        int $code = Response::HTTP_UNAUTHORIZED
    ) {
        parent::__construct($message, $code);
    }
}
