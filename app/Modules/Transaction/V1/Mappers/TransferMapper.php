<?php

namespace App\Modules\Transaction\V1\Mappers;

use App\Modules\Transaction\V1\Http\Dtos\TransferDto;
use App\Modules\Transaction\V1\Http\Requests\TransferRequest;

class TransferMapper
{
    public function fromRequestToDto(TransferRequest $request): TransferDto
    {
        return new TransferDto(
            payerUuid: $request->input('payer'),
            payeeUuid: $request->input('payee'),
            amount: $request->input('value'),
        );
    }

    public function getResourceResponse(): array
    {
        return [
            'message' => 'Transfer send successfully',
            'data' => [],
        ];
    }
}
