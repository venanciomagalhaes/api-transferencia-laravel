<?php

namespace App\Modules\Transaction\V1\Services;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Exceptions\InsufficientBalanceToSendTransactionException;
use App\Modules\Transaction\V1\Http\Dtos\TransferDto;
use App\Modules\User\V1\Models\User;

readonly class TransferSuficientBalanceValidatorService
{
    public function __construct(
        private LoggerServiceInterface $logger,
    ) {}

    /**
     * Verifica se o pagador possui saldo suficiente para realizar a transferência.
     *
     * Caso contrário, registra aviso e lança exceção.
     *
     * @throws InsufficientBalanceToSendTransactionException
     */
    public function validateSufficientBalance(User $payer, TransferDto $dto): void
    {
        if ($payer->wallet->amount < $dto->getAmount()) {
            $this->logger->warning("User {$payer->uuid} has insufficient balance: {$payer->wallet->amount}, required: {$dto->getAmount()}.");
            throw new InsufficientBalanceToSendTransactionException;
        }
        $this->logger->debug("User {$payer->uuid} has sufficient balance: {$payer->wallet->amount}.");
    }
}
