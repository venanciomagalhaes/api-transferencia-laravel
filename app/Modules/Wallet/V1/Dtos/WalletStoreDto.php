<?php

namespace App\Modules\Wallet\V1\Dtos;

class WalletStoreDto
{
    public function __construct(
        private int $userId,
        private float $amount
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
