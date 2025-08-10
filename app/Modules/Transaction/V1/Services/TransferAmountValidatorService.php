<?php

namespace App\Modules\Transaction\V1\Services;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Exceptions\TransferAmountMustBeGreaterThanZeroException;
use App\Modules\Transaction\V1\Http\Dtos\TransferDto;

readonly class TransferAmountValidatorService
{
    public function __construct(
        private LoggerServiceInterface $logger,
    )
    {
    }

    /**
     * Valida se o valor da transferência é maior que zero.
     *
     * Caso contrário, registra erro e lança exceção.
     *
     * @throws TransferAmountMustBeGreaterThanZeroException
     */
    public function validateAmount(TransferDto $dto): void
    {
        if ($dto->getAmount() <= 0) {
            $this->logger->error("Invalid transfer amount: {$dto->getAmount()}");
            throw new TransferAmountMustBeGreaterThanZeroException('Transfer amount must be greater than zero.');
        }
    }

}
