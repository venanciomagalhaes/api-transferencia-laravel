<?php

namespace Tests\Unit\Modules\Transaction\V1\Exceptions;

use App\Modules\Transaction\V1\Exceptions\DoesNotHavePermissionToReceiveTransactionException;
use Symfony\Component\HttpFoundation\Response;

it('deve instanciar a exceção com mensagem e código padrão', function () {
    $exception = new DoesNotHavePermissionToReceiveTransactionException();

    expect($exception)->toBeInstanceOf(DoesNotHavePermissionToReceiveTransactionException::class)
        ->and($exception->getMessage())->toBe('This user type does not have permission to receive transactions.')
        ->and($exception->getCode())->toBe(Response::HTTP_UNAUTHORIZED);
});

it('deve instanciar a exceção com mensagem e código customizados', function () {
    $exception = new DoesNotHavePermissionToReceiveTransactionException('Mensagem customizada', 123);

    expect($exception)->toBeInstanceOf(DoesNotHavePermissionToReceiveTransactionException::class)
        ->and($exception->getMessage())->toBe('Mensagem customizada')
        ->and($exception->getCode())->toBe(123);
});
