<?php

namespace App\Modules\Transaction\V1\Services;

use App\Modules\Common\V1\Services\Http\HttpServiceInterface;
use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Exceptions\UnauthorizedTransferException;

readonly class TransferVerifyPayerAuthorizationService
{
    public function __construct(
        private LoggerServiceInterface $logger,
        private HttpServiceInterface $httpService,
    )
    {
    }

    /**
     * Consulta serviço externo para verificar autorização da transferência.
     *
     * Em caso de negação, registra erro e lança exceção.
     *
     * @throws UnauthorizedTransferException
     */
    public function verifyAuthorization(): void
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
