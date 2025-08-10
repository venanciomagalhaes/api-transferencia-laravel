<?php

namespace Tests\Unit\Modules\Wallet\V1\Mappers;

use App\Modules\User\V1\Events\UserCreated;
use App\Modules\User\V1\Models\User;
use App\Modules\Wallet\V1\Mappers\WalletMapper;
use App\Modules\Wallet\V1\Dtos\WalletStoreDto;
use Mockery;
use Ramsey\Uuid\Uuid;

it('deve mapear o evento UserCreated para WalletStoreDto', function () {
    $userMock = Mockery::mock(User::class);
    $userMock->shouldReceive('getAttribute')->with('id')->andReturn(10);

    $event = new UserCreated(
        user: $userMock,
        amount: 1000
    );

    $mapper = new WalletMapper();
    $dto = $mapper->fromEventToDto($event);

    expect($dto)->toBeInstanceOf(WalletStoreDto::class)
        ->and($dto->getUserId())->toBe(10)
        ->and($dto->getAmount())->toBe(1000.0);
});

it('deve mapear o WalletStoreDto para um array de persistência', function () {
    $dto = new WalletStoreDto(
        userId: 10,
        amount: 1000
    );

    $mapper = new WalletMapper();
    $data = $mapper->fromDtoToPersistency($dto);

    expect($data)->toHaveKeys(['uuid', 'user_id', 'amount'])
        ->and(Uuid::isValid($data['uuid']))->toBeTrue()
        ->and($data['user_id'])->toBe(10)
        ->and($data['amount'])->toBe(1000.0);
});
