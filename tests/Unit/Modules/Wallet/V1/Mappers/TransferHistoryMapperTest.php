<?php

namespace Tests\Unit\Modules\Wallet\V1\Mappers;

use App\Modules\User\V1\Models\User;
use App\Modules\Wallet\V1\Mappers\TransferHistoryMapper;
use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Wallet\V1\Dtos\TransactionHistoryDto;
use Mockery;
use Ramsey\Uuid\Uuid;

it('deve mapear TransactionHistoryDto para array de persistência', function () {
    $dto = new TransactionHistoryDto(
        payerId: 5,
        payeeId: 10,
        amount: 2500
    );

    $mapper = new TransferHistoryMapper();
    $data = $mapper->fromDtoToPersistency($dto);

    expect($data)
        ->toHaveKeys(['uuid', 'amount', 'payer_wallet_id', 'payee_wallet_id'])
        ->and($data['amount'])->toBe(2500)
        ->and($data['payer_wallet_id'])->toBe(5)
        ->and($data['payee_wallet_id'])->toBe(10)
        ->and(Uuid::isValid($data['uuid']))->toBeTrue();
});

it('deve mapear TransferSuccessfullyEvent para TransactionHistoryDto', function () {
    $payer = Mockery::mock(User::class);
    $payer->shouldReceive('getAttribute')->with('id')->andReturn(7);

    $payee = Mockery::mock(User::class);
    $payee->shouldReceive('getAttribute')->with('id')->andReturn(12);

    $event = Mockery::mock(TransferSuccessfullyEvent::class);
    $event->shouldReceive('getPayer')->andReturn($payer);
    $event->shouldReceive('getPayee')->andReturn($payee);
    $event->shouldReceive('getAmount')->andReturn(1500);

    $mapper = new TransferHistoryMapper();
    $dto = $mapper->fromEventToDto($event);

    expect($dto)
        ->toBeInstanceOf(TransactionHistoryDto::class)
        ->and($dto->getPayerId())->toBe(7)
        ->and($dto->getPayeeId())->toBe(12)
        ->and($dto->getAmount())->toBe(1500);
});
