<?php

namespace App\Modules\Transaction\V1\Services;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Permissions\V1\Enums\PermissionsNameEnum;
use App\Modules\Transaction\V1\Exceptions\DoesNotHavePermissionToReceiveTransactionException;
use App\Modules\Transaction\V1\Exceptions\DoesNotHavePermissionToSendTransactionException;
use App\Modules\User\V1\Models\User;

readonly class TransferPermissionsValidatorService
{
    public function __construct(
        private LoggerServiceInterface $logger,
    ) {}

    /**
     * Verifica se o pagador possui permissão para enviar transações.
     *
     * Caso não possua, registra aviso e lança exceção.
     *
     * @throws DoesNotHavePermissionToSendTransactionException
     */
    public function validatePayerPermission(User $payer): void
    {
        if (! $payer->hasPermission(PermissionsNameEnum::SEND_TRANSACTION)) {
            $this->logger->warning("User {$payer->uuid} does not have permission to send transactions.");
            throw new DoesNotHavePermissionToSendTransactionException(
                'This payer does not have permission to send transactions.'
            );
        }
        $this->logger->debug("User {$payer->uuid} has permission to send transactions.");
    }

    /**
     * Verifica se o recebedor possui permissão para receber transações.
     *
     * Caso não possua, registra aviso e lança exceção.
     *
     * @throws DoesNotHavePermissionToReceiveTransactionException
     */
    public function validatePayeePermission(User $payee): void
    {
        if (! $payee->hasPermission(PermissionsNameEnum::RECEIVE_TRANSACTION)) {
            $this->logger->warning("User {$payee->uuid} does not have permission to receive transactions.");
            throw new DoesNotHavePermissionToReceiveTransactionException(
                'This payee does not have permission to receive transactions.'
            );
        }
        $this->logger->debug("User {$payee->uuid} has permission to receive transactions.");
    }
}
