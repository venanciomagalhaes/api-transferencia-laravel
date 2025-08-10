<?php

namespace App\Modules\Transaction\V1\Services;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Exceptions\PayerAndPayeeAreTheSameUserException;
use App\Modules\Transaction\V1\Http\Dtos\TransferDto;

readonly class TransferDifferentUsersValidatorService
{
    public function __construct(
        private LoggerServiceInterface $logger,
    )
    {
    }

    /**
     * Valida se pagador e recebedor são usuários diferentes.
     *
     * Caso sejam iguais, registra erro e lança exceção.
     *
     * @throws PayerAndPayeeAreTheSameUserException
     */
    public function validateDifferentUsers(TransferDto $dto): void
    {
        if ($dto->getPayerUuid() === $dto->getPayeeUuid()) {
            $this->logger->error("Transfer failed: payer and payee are the same user ({$dto->getPayerUuid()}).");
            throw new PayerAndPayeeAreTheSameUserException('Payer and payee must be different users.');
        }
    }
}
