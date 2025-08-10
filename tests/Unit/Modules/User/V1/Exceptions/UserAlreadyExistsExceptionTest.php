<?php

namespace Tests\Unit\Modules\User\V1\Exceptions;

use App\Modules\User\V1\Exceptions\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Response;

it('instancia UserAlreadyExistsException com valores padrão', function () {
    $exception = new UserAlreadyExistsException;

    expect($exception->getMessage())->toBe('This user already exists')
        ->and($exception->getCode())->toBe(Response::HTTP_CONFLICT);
});

it('instancia UserAlreadyExistsException com valores customizados', function () {
    $message = 'Usuário já cadastrado';
    $code = 409;

    $exception = new UserAlreadyExistsException($message, $code);

    expect($exception->getMessage())->toBe($message)
        ->and($exception->getCode())->toBe($code);
});
