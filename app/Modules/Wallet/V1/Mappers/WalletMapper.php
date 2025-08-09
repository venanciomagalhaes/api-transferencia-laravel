<?php

namespace App\Modules\Wallet\V1\Mappers;

use App\Modules\User\V1\Events\UserCreated;
use App\Modules\Wallet\V1\Dtos\WalletStoreDto;
use App\Modules\Wallet\V1\Models\Wallet;
use Ramsey\Uuid\Uuid;

class WalletMapper
{

    public function fromEventToDto(UserCreated $event): WalletStoreDto
    {
        return new WalletStoreDto(
            userId: $event->user->id,
            amount: $event->amount
        );
    }

    public function fromDtoToPersistency(WalletStoreDto $dto): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'user_id' => $dto->getUserId(),
            'amount' => $dto->getAmount()
        ];
    }
}
