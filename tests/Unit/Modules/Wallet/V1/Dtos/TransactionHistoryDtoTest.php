<?php

namespace Tests\Unit\Modules\Wallet\V1\Dtos;

use App\Modules\Wallet\V1\Dtos\TransactionHistoryDto;

it('deve criar o DTO e retornar os valores corretamente', function () {
    $dto = new TransactionHistoryDto(
        payerId: 15,
        payeeId: 20,
        amount: 5000
    );

    expect($dto)->toBeInstanceOf(TransactionHistoryDto::class)
        ->and($dto->getPayerId())->toBe(15)
        ->and($dto->getPayeeId())->toBe(20)
        ->and($dto->getAmount())->toBe(5000);
});
