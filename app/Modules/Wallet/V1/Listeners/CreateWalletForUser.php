<?php

namespace App\Modules\Wallet\V1\Listeners;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\User\V1\Events\UserCreated;
use App\Modules\Wallet\V1\Mappers\WalletMapper;
use App\Modules\Wallet\V1\Repositories\WalletRepositoryInterface;


class CreateWalletForUser
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private WalletRepositoryInterface $walletRepository,
        private WalletMapper $walletMapper,
        private LoggerServiceInterface $logger
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        try {
            $this->logger->info('Starting wallet creation.');
            $dto = $this->walletMapper->fromEventToDto($event);
            $data = $this->walletMapper->fromDtoToPersistency($dto);
            $this->walletRepository->create($data);
            $this->logger->info('Wallet created successfully.');
        } catch (\Exception $exception) {
            $this->logger->error('Error while creating wallet: '.$exception->getMessage());
            throw $exception;
        }
    }
}
