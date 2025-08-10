<?php

namespace App\Modules\Transaction\V1\Action;

use App\Modules\Common\V1\Services\Http\HttpServiceInterface;
use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
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
use App\Modules\Transaction\V1\Services\TransferAmountValidatorService;
use App\Modules\Transaction\V1\Services\TransferDifferentUsersValidatorService;
use App\Modules\Transaction\V1\Services\TransferPermissionsValidatorService;
use App\Modules\Transaction\V1\Services\TransferSuficientBalanceValidatorService;
use App\Modules\Transaction\V1\Services\TransferVerifyPayerAuthorizationService;
use App\Modules\User\V1\Models\User;
use App\Modules\User\V1\Repositories\UserRepositoryInterface;
use App\Modules\Wallet\V1\Repositories\WalletRepositoryInterface;

/**
 * Classe responsável por executar a ação de transferência entre usuários,
 * realizando validações, controle de permissões, verificação de saldo,
 * autorização externa e disparo de evento assíncrono após o sucesso da transferência.
 */
readonly class TransferAction
{
    public function __construct(
        private UserRepositoryInterface                $userRepository,
        private TransactionServiceInterface            $transactionService,
        private WalletRepositoryInterface              $walletRepository,
        private TransferAmountValidatorService         $transferAmountValidatorService,
        private TransferDifferentUsersValidatorService $transferValidatorDifferentUsersService,
        private TransferVerifyPayerAuthorizationService  $transferVerifyPayerAuthorizationService,
        private TransferPermissionsValidatorService  $transferPermissionsValidatorService,
        private TransferSuficientBalanceValidatorService  $transferSuficientBalanceValidatorService,
        private LoggerServiceInterface                 $logger,
    ) {}

    /**
     * Executa a transferência de valor entre pagador e recebedor.
     *
     * Realiza as seguintes etapas:
     * - Valida o valor da transferência (deve ser maior que zero).
     * - Verifica se pagador e recebedor são usuários diferentes.
     * - Abre uma transação para garantir atomicidade.
     * - Busca os usuários pagador e recebedor.
     * - Valida permissões dos usuários para enviar e receber transações.
     * - Verifica se o pagador possui saldo suficiente.
     * - Consulta serviço externo para autorização da transferência.
     * - Realiza a transferência na carteira dos usuários.
     * - Dispara evento TransferSuccessfullyEvent, que trata o envio de mensagens
     *   de forma assíncrona com re-tentativas em caso de falha e que cria, de forma sincrona, o historico
     *   de transação entre as carteiras.
     *
     * @param TransferDto $dto Dados da transferência.
     * @throws TransferAmountMustBeGreaterThanZeroException
     * @throws PayerAndPayeeAreTheSameUserException
     */
    public function handle(TransferDto $dto): void
    {
        $this->logger->info("Starting transfer of {$dto->getAmount()} from payer {$dto->getPayerUuid()} to payee {$dto->getPayeeUuid()}.");

        $this->transferAmountValidatorService->validateAmount($dto);
        $this->transferValidatorDifferentUsersService->validateDifferentUsers($dto);

        $this->transactionService->run(function () use ($dto) {
            $payer = $this->getPayer($dto);
            $payee = $this->getPayee($dto);

            $this->transferPermissionsValidatorService->validatePayerPermission($payer);
            $this->transferPermissionsValidatorService->validatePayeePermission($payee);
            $this->transferSuficientBalanceValidatorService->validateSufficientBalance($payer, $dto);
            $this->transferVerifyPayerAuthorizationService->verifyAuthorization();

            $this->logger->info('Authorization granted, proceeding with transfer...');

            $this->walletRepository->makeTransfer($payer, $dto->getAmount(), $payee);

            $this->logger->info('Transfer completed successfully.');

            event(new TransferSuccessfullyEvent(payee: $payee, amount: $dto->getAmount(), payer: $payer));
        });
    }




    /**
     * Busca o usuário pagador pelo UUID informado no DTO.
     *
     * @return User Usuário pagador.
     */
    private function getPayer(TransferDto $dto): User
    {
        $this->logger->debug("Fetching payer user: {$dto->getPayerUuid()}");

        return $this->userRepository->findByUuid($dto->getPayerUuid());
    }

    /**
     * Busca o usuário recebedor pelo UUID informado no DTO e retorna com a carteira bloqueada (lock pessimista).
     *
     * @return User Usuário recebedor.
     */
    private function getPayee(TransferDto $dto): User
    {
        $this->logger->debug("Fetching payee user: {$dto->getPayeeUuid()}");

        return $this->userRepository->findByUuidWithWalletLockForUpdate($dto->getPayeeUuid());
    }
}
