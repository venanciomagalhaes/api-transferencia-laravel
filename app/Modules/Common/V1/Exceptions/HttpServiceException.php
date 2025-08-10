<?php

namespace App\Modules\Common\V1\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Exceção usada para erros durante comunicação com outros serviços HTTP.
 */
class HttpServiceException extends AbstractBusinessException
{
    private const DEFAULT_MESSAGE = 'An error occurred during request to another service';

    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}
