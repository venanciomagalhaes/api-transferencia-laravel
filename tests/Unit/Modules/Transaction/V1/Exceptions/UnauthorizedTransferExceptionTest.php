<?php

namespace Tests\Unit\Modules\Transaction\V1\Exceptions;

use App\Modules\Transaction\V1\Exceptions\UnauthorizedTransferException;
use Symfony\Component\HttpFoundation\Response;

it('deve instanciar a exceção com mensagem e código padrão', function () {
    $exception = new UnauthorizedTransferException;

    expect($exception)->toBeInstanceOf(UnauthorizedTransferException::class)
        ->and($exception->getMessage())->toBe('This payer is not authorized to perform this transfer.')
        ->and($exception->getCode())->toBe(Response::HTTP_UNAUTHORIZED);
});

it('deve instanciar a exceção com mensagem e código customizados', function () {
    $exception = new UnauthorizedTransferException('Mensagem customizada', 401);

    expect($exception)->toBeInstanceOf(UnauthorizedTransferException::class)
        ->and($exception->getMessage())->toBe('Mensagem customizada')
        ->and($exception->getCode())->toBe(401);
});
