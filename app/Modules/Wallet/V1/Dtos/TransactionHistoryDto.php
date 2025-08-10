<?php

namespace App\Modules\Wallet\V1\Dtos;

class TransactionHistoryDto
{
    public function __construct(
        private int $payerId,
        private int $payeeId,
        private int $amount,
    )
    {
    }

    public function getPayerId(): int
    {
        return $this->payerId;
    }

    public function getPayeeId(): int
    {
        return $this->payeeId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }


}
