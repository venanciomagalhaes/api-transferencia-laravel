<?php

namespace App\Modules\Wallet\V1\Listeners;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Wallet\V1\Mappers\TransferHistoryMapper;
use App\Modules\Wallet\V1\Repositories\TransactionHistoryRepositoryInterface;

readonly class CreateTransactionHistory
{
    public function __construct(
        private LoggerServiceInterface $logger,
        private TransactionHistoryRepositoryInterface $transactionHistoryRepository,
        private TransferHistoryMapper $mapper,
    ) {
        //
    }

    public function handle(TransferSuccessfullyEvent $event): void
    {
        try {
            $this->logger->info('Starting transaction history creation', [
                'payer_id' => $event->getPayer()->id,
                'payee_id' => $event->getPayee()->id,
                'amount' => $event->getAmount(),
            ]);

            $dto = $this->mapper->fromEventToDto($event);
            $data = $this->mapper->fromDtoToPersistency($dto);

            $this->transactionHistoryRepository->create($data);

            $this->logger->info('Transaction history created successfully', [
                'payer_id' => $event->getPayer()->id,
                'payee_id' => $event->getPayee()->id,
                'amount' => $event->getAmount(),
            ]);
        } catch (\Throwable $exception) {
            $this->logger->error('Error creating transaction history', [
                'message' => $exception->getMessage(),
                'payer_id' => $event->getPayer()->id,
                'payee_id' => $event->getPayee()->id,
                'amount' => $event->getAmount(),
                'exception' => $exception,
            ]);
            throw $exception;
        }
    }
}
