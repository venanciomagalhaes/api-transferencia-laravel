<?php

namespace App\Dtos\V1\Transfer;

class TransferDto
{
    private int $payeeId;

    private int $payerId;

    public function __construct(
        private float $value,
        private string $payerUuid,
        private string $payeeUuid
    ) {}

    public function getPayeeUuid(): string
    {
        return $this->payeeUuid;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getPayerUuid(): string
    {
        return $this->payerUuid;
    }

    public function getPayeeId(): int
    {
        return $this->payeeId;
    }

    public function setPayeeId(int $payeeId): void
    {
        $this->payeeId = $payeeId;
    }

    public function getPayerId(): int
    {
        return $this->payerId;
    }

    public function setPayerId(int $payerId): void
    {
        $this->payerId = $payerId;
    }
}
