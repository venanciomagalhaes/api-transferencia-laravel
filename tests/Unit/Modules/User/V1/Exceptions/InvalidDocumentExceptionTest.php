<?php

namespace Tests\Unit\Modules\User\V1\Exceptions;

use App\Modules\User\V1\Exceptions\InvalidDocumentException;
use Symfony\Component\HttpFoundation\Response;

it('instancia InvalidDocumentException com valores padrão', function () {
    $exception = new InvalidDocumentException();

    expect($exception->getMessage())->toBe('This document is invalid')
        ->and($exception->getCode())->toBe(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('instancia InvalidDocumentException com valores customizados', function () {
    $message = 'Documento inválido personalizado';
    $code = 422;

    $exception = new InvalidDocumentException($message, $code);

    expect($exception->getMessage())->toBe($message)
        ->and($exception->getCode())->toBe($code);
});
