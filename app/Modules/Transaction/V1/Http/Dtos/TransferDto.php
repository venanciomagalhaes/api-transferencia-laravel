<?php

namespace App\Modules\Transaction\V1\Http\Dtos;

readonly class TransferDto
{
    public function __construct(
        private string $payerUuid,
        private string $payeeUuid,
        private float  $amount,
    )
    {
    }

    public function getPayerUuid(): string
    {
        return $this->payerUuid;
    }

    public function getPayeeUuid(): string
    {
        return $this->payeeUuid;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

}
