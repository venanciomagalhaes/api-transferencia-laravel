<?php

namespace App\Mappers\V1;

use App\Dtos\V1\Transfer\TransferDto;
use App\Helpers\UuidHelper;
use App\Http\Requests\V1\TransferRequest;
use App\Http\Resources\TransferResource;
use App\Models\Transfer;

class TransferMapper
{

    public static function toResource(Transfer $transfer): array
    {
        return [
            'message'=> 'Transfer make successfully',
            'data' => new TransferResource($transfer),
        ];
    }

    public static function toArrayFromDto(TransferDto $dto): array
    {
        return [
            'uuid' => UuidHelper::generate(),
            'payer_id' => $dto->getPayerId(),
            'payee_id' => $dto->getPayeeId(),
            'amount' => $dto->getValue()
        ];
    }

    public static function toTransferDto(TransferRequest $request): TransferDto
    {
        return new TransferDto(
            value: $request->input('value'),
            payerUuid: $request->input('payer'),
            payeeUuid: $request->input('payee'),
        );
    }
}
