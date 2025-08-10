<?php

namespace Tests\Unit\Modules\Wallet\V1\Dtos;

use App\Modules\Wallet\V1\Dtos\WalletStoreDto;

it('deve armazenar e retornar os valores corretos', function () {
    $dto = new WalletStoreDto(
        userId: 10,
        amount: 150.75
    );

    expect($dto->getUserId())->toBe(10)
        ->and($dto->getAmount())->toBe(150.75);
});
