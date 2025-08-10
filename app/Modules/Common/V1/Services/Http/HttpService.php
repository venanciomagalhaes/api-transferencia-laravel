<?php

namespace App\Modules\Common\V1\Services\Http;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class HttpService implements HttpServiceInterface
{
    protected int $retries = 3;

    protected int $retryDelayMs = 100;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @throws Exception
     */
    public function get(string $url): array
    {
        return $this->makeRequest('get', $url);
    }

    /**
     * @throws Exception
     */
    public function post(string $url, array $body = []): array
    {
        return $this->makeRequest('post', $url, $body);
    }

    /**
     * @throws Exception
     */
    public function put(string $url, array $body = []): array
    {
        return $this->makeRequest('put', $url, $body);
    }

    /**
     * @throws Exception
     */
    public function delete(string $url, array $body = []): array
    {
        return $this->makeRequest('delete', $url, $body);
    }

    /**
     * @throws Exception
     */
    protected function makeRequest(string $method, string $url, array $body = []): array
    {
        try {
            $response = Http::acceptJson()->{$method}($url, $body);

            if ($response->status() == Response::HTTP_NO_CONTENT) {
                return [];
            }

            return $response->json();

        } catch (RequestException $e) {
            $this->logger->error('HttpService:'.$method.' Error: '.$e->getMessage());
            throw $e;
        }

    }
}
