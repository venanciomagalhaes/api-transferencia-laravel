<?php

namespace Tests\Unit\Modules\Transaction\V1\Mappers;

use App\Modules\Transaction\V1\Http\Dtos\TransferDto;
use App\Modules\Transaction\V1\Http\Requests\TransferRequest;
use App\Modules\Transaction\V1\Mappers\TransferMapper;
use Mockery;

it('deve mapear TransferRequest para TransferDto', function () {
    $request = Mockery::mock(TransferRequest::class);
    $request->shouldReceive('input')->with('payer')->andReturn('payer-uuid-123');
    $request->shouldReceive('input')->with('payee')->andReturn('payee-uuid-456');
    $request->shouldReceive('input')->with('value')->andReturn(150.25);

    $mapper = new TransferMapper;
    $dto = $mapper->fromRequestToDto($request);

    expect($dto)->toBeInstanceOf(TransferDto::class)
        ->and($dto->getPayerUuid())->toBe('payer-uuid-123')
        ->and($dto->getPayeeUuid())->toBe('payee-uuid-456')
        ->and($dto->getAmount())->toBe(150.25);
});

it('deve retornar a resposta esperada do recurso', function () {
    $mapper = new TransferMapper;
    $response = $mapper->getResourceResponse();

    expect($response)->toBeArray()
        ->toMatchArray([
            'message' => 'Transfer send successfully',
            'data' => [],
        ]);
});
