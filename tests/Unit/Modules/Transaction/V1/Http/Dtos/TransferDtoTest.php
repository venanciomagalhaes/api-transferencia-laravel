<?php

namespace Tests\Unit\Modules\Transaction\V1\Http\Dtos;

use App\Modules\Transaction\V1\Http\Dtos\TransferDto;

it('deve armazenar e retornar os valores corretos do TransferDto', function () {
    $dto = new TransferDto(
        payerUuid: 'payer-uuid-123',
        payeeUuid: 'payee-uuid-456',
        amount: 250.50,
    );

    expect($dto->getPayerUuid())->toBe('payer-uuid-123')
        ->and($dto->getPayeeUuid())->toBe('payee-uuid-456')
        ->and($dto->getAmount())->toBe(250.50);
});
