<?php

namespace App\Mappers\V1;

use App\Helpers\UuidHelper;

class WalletMapper
{
    public static function toArrayForStore(int $userId): array
    {
        return [
            'user_id' => $userId,
            'amount' => 1000.00,
            'uuid' => UuidHelper::generate(),
        ];
    }
}
