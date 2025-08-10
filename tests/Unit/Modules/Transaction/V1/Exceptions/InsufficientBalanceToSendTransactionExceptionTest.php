<?php

namespace Tests\Unit\Modules\Transaction\V1\Exceptions;

use App\Modules\Transaction\V1\Exceptions\InsufficientBalanceToSendTransactionException;
use Symfony\Component\HttpFoundation\Response;

it('deve instanciar a exceção com mensagem e código padrão', function () {
    $exception = new InsufficientBalanceToSendTransactionException();

    expect($exception)->toBeInstanceOf(InsufficientBalanceToSendTransactionException::class)
        ->and($exception->getMessage())->toBe('The payer does not have enough balance to perform this transaction.')
        ->and($exception->getCode())->toBe(Response::HTTP_BAD_REQUEST);
});

it('deve instanciar a exceção com mensagem e código customizados', function () {
    $exception = new InsufficientBalanceToSendTransactionException('Mensagem customizada', 456);

    expect($exception)->toBeInstanceOf(InsufficientBalanceToSendTransactionException::class)
        ->and($exception->getMessage())->toBe('Mensagem customizada')
        ->and($exception->getCode())->toBe(456);
});
