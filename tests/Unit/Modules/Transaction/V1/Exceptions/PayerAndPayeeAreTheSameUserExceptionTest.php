<?php

namespace Tests\Unit\Modules\Transaction\V1\Exceptions;

use App\Modules\Transaction\V1\Exceptions\PayerAndPayeeAreTheSameUserException;
use Symfony\Component\HttpFoundation\Response;

it('deve instanciar a exceção com mensagem e código padrão', function () {
    $exception = new PayerAndPayeeAreTheSameUserException();

    expect($exception)->toBeInstanceOf(PayerAndPayeeAreTheSameUserException::class)
        ->and($exception->getMessage())->toBe('The payer and payee are the same user')
        ->and($exception->getCode())->toBe(Response::HTTP_BAD_REQUEST);
});

it('deve instanciar a exceção com mensagem e código customizados', function () {
    $exception = new PayerAndPayeeAreTheSameUserException('Mensagem customizada', 789);

    expect($exception)->toBeInstanceOf(PayerAndPayeeAreTheSameUserException::class)
        ->and($exception->getMessage())->toBe('Mensagem customizada')
        ->and($exception->getCode())->toBe(789);
});
