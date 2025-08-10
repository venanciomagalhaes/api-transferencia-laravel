<?php

namespace App\Modules\Transaction\V1\Action;

use App\Modules\Common\V1\Services\Http\HttpServiceInterface;
use App\Modules\Common\V1\Services\Transaction\TransactionServiceInterface;
use App\Modules\Permissions\V1\Enums\PermissionsNameEnum;
use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Transaction\V1\Exceptions\DoesNotHavePermissionToReceiveTransactionException;
use App\Modules\Transaction\V1\Exceptions\DoesNotHavePermissionToSendTransactionException;
use App\Modules\Transaction\V1\Exceptions\InsufficientBalanceToSendTransactionException;
use App\Modules\Transaction\V1\Exceptions\PayerAndPayeeAreTheSameUserException;
use App\Modules\Transaction\V1\Exceptions\TransferAmountMustBeGreaterThanZeroException;
use App\Modules\Transaction\V1\Exceptions\UnauthorizedTransferException;
use App\Modules\Transaction\V1\Http\Dtos\TransferDto;
use App\Modules\User\V1\Models\User;
use App\Modules\User\V1\Repositories\UserRepositoryInterface;
use App\Modules\Wallet\V1\Repositories\WalletRepositoryInterface;
use Psr\Log\LoggerInterface;

readonly class TransferAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private HttpServiceInterface $httpService,
        private TransactionServiceInterface $transactionService,
        private WalletRepositoryInterface $walletRepository,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws PayerAndPayeeAreTheSameUserException
     * @throws TransferAmountMustBeGreaterThanZeroException
     */
    public function handle(TransferDto $dto): void
    {
        $this->logger->info("Starting transfer of {$dto->getAmount()} from payer {$dto->getPayerUuid()} to payee {$dto->getPayeeUuid()}.");

        $this->validateAmount($dto);
        $this->validateDifferentUsers($dto);

        $this->transactionService->run(function () use ($dto) {
            $payer = $this->getPayer($dto);
            $payee = $this->getPayee($dto);

            $this->validatePayerPermission($payer);
            $this->validatePayeePermission($payee);
            $this->validateSufficientBalance($payer, $dto);
            $this->verifyAuthorization();

            $this->logger->info('Authorization granted, proceeding with transfer...');

            $this->walletRepository->makeTransfer($payer, $dto->getAmount(), $payee);

            $this->logger->info('Transfer completed successfully.');

            event(new TransferSuccessfullyEvent($payee, $dto->getAmount()));
        });
    }

    /**
     * @throws TransferAmountMustBeGreaterThanZeroException
     */
    private function validateAmount(TransferDto $dto): void
    {
        if ($dto->getAmount() <= 0) {
            $this->logger->error("Invalid transfer amount: {$dto->getAmount()}");
            throw new TransferAmountMustBeGreaterThanZeroException('Transfer amount must be greater than zero.');
        }
    }

    /**
     * @throws PayerAndPayeeAreTheSameUserException
     */
    private function validateDifferentUsers(TransferDto $dto): void
    {
        if ($dto->getPayerUuid() === $dto->getPayeeUuid()) {
            $this->logger->error("Transfer failed: payer and payee are the same user ({$dto->getPayerUuid()}).");
            throw new PayerAndPayeeAreTheSameUserException('Payer and payee must be different users.');
        }
    }

    private function getPayer(TransferDto $dto): User
    {
        $this->logger->debug("Fetching payer user: {$dto->getPayerUuid()}");

        return $this->userRepository->findByUuid($dto->getPayerUuid());
    }

    private function getPayee(TransferDto $dto): User
    {
        $this->logger->debug("Fetching payee user: {$dto->getPayeeUuid()}");

        return $this->userRepository->findByUuid($dto->getPayeeUuid());
    }

    private function validatePayerPermission(User $payer): void
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
     * @throws DoesNotHavePermissionToReceiveTransactionException
     */
    private function validatePayeePermission(User $payee): void
    {
        if (! $payee->hasPermission(PermissionsNameEnum::RECEIVE_TRANSACTION)) {
            $this->logger->warning("User {$payee->uuid} does not have permission to receive transactions.");
            throw new DoesNotHavePermissionToReceiveTransactionException(
                'This payee does not have permission to receive transactions.'
            );
        }
        $this->logger->debug("User {$payee->uuid} has permission to receive transactions.");
    }

    /**
     * @throws InsufficientBalanceToSendTransactionException
     */
    private function validateSufficientBalance(User $payer, TransferDto $dto): void
    {
        if ($payer->wallet->amount < $dto->getAmount()) {
            $this->logger->warning("User {$payer->uuid} has insufficient balance: {$payer->wallet->amount}, required: {$dto->getAmount()}.");
            throw new InsufficientBalanceToSendTransactionException;
        }
        $this->logger->debug("User {$payer->uuid} has sufficient balance: {$payer->wallet->amount}.");
    }

    /**
     * @throws UnauthorizedTransferException
     */
    private function verifyAuthorization(): void
    {
        $this->logger->info('Checking external authorization service...');
        $authorization = $this->httpService->get(env('AUTHORIZE_TRANSACTION_ENDPOINT_URL'));

        $this->logger->debug('Authorization response: '.json_encode($authorization));

        $isAuthorized = $authorization['status'] && $authorization['data']['authorization'];

        if (! $isAuthorized) {
            $this->logger->error('Transfer not authorized by external service.');
            throw new UnauthorizedTransferException;
        }

        $this->logger->info('Transfer authorized by external service.');
    }
}
