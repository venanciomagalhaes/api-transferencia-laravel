<?php

namespace Tests\Unit\Modules\Transaction\V1\Exceptions;

use App\Modules\Transaction\V1\Exceptions\TransferAmountMustBeGreaterThanZeroException;
use Symfony\Component\HttpFoundation\Response;

it('deve instanciar a exceção com mensagem e código padrão', function () {
    $exception = new TransferAmountMustBeGreaterThanZeroException();

    expect($exception)->toBeInstanceOf(TransferAmountMustBeGreaterThanZeroException::class)
        ->and($exception->getMessage())->toBe('Transfer amount must be greater than zero')
        ->and($exception->getCode())->toBe(Response::HTTP_BAD_REQUEST);
});

it('deve instanciar a exceção com mensagem e código customizados', function () {
    $exception = new TransferAmountMustBeGreaterThanZeroException('Mensagem customizada', 400);

    expect($exception)->toBeInstanceOf(TransferAmountMustBeGreaterThanZeroException::class)
        ->and($exception->getMessage())->toBe('Mensagem customizada')
        ->and($exception->getCode())->toBe(400);
});
