<?php

namespace App\Modules\Transaction\V1\Listeners;

use App\Modules\Common\V1\Services\Http\HttpServiceInterface;
use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Log\LoggerInterface;

class SendTransferSuccessfullyNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 5;

    public int $backoff = 10;

    public function __construct(
        private readonly HttpServiceInterface $httpService,
        private readonly LoggerInterface $logger
    ) {}

    public function handle(TransferSuccessfullyEvent $event): void
    {
        try {
            $this->logger->info('Sending transfer notification...');
            $this->httpService->post(env('NOTIFICATION_SERVICE_ENDPOINT_URL'), []);
            $this->logger->info('Notification sent successfully.');
        } catch (\Exception $exception) {
            $this->logger->error('Sending notification failed: '.$exception->getMessage());
            throw $exception;
        }
    }

    /**
     * Handle a job failure (after max attempts).
     */
    public function failed(TransferSuccessfullyEvent $event, \Throwable $exception): void
    {
        $this->logger->error('Notification permanently failed after retries. Error: '.$exception->getMessage());
    }
}
