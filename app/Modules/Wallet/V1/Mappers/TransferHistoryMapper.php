<?php

namespace App\Modules\Wallet\V1\Mappers;

use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Wallet\V1\Dtos\TransactionHistoryDto;
use Ramsey\Uuid\Uuid;

class TransferHistoryMapper
{
    public function fromDtoToPersistency(TransactionHistoryDto $dto): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'amount' => $dto->getAmount(),
            'payer_wallet_id' => $dto->getPayerId(),
            'payee_wallet_id' => $dto->getPayeeId(),
        ];
    }

    public function fromEventToDto(TransferSuccessfullyEvent $event): TransactionHistoryDto
    {
        return new TransactionHistoryDto(
            payerId: $event->getPayer()->id,
            payeeId: $event->getPayee()->id,
            amount: $event->getAmount()
        );
    }
}
