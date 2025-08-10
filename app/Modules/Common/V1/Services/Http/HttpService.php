<?php

namespace App\Modules\Common\V1\Services\Http;

use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Transaction\V1\Exceptions\UnauthorizedTransferException;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serviço responsável por realizar requisições HTTP com suporte a tentativas automáticas (retry).
 *
 * Esta classe encapsula chamadas HTTP (GET, POST, PUT, DELETE) usando o Laravel HTTP Client,
 * implementando uma lógica de retry nativa para aumentar a resiliência das requisições,
 * além de logar possíveis erros para facilitar o diagnóstico.
 *
 * Pode lançar exceções em caso de falha após todas as tentativas.
 */
class HttpService implements HttpServiceInterface
{
    /**
     * Número máximo de tentativas em caso de falha na requisição HTTP.
     */
    protected int $retries = 3;

    /**
     * Tempo de espera (em milissegundos) entre cada tentativa de retry.
     */
    protected int $retryDelayMs = 100;

    public function __construct(
        private readonly LoggerServiceInterface $logger
    ) {}

    /**
     * Faz uma requisição HTTP GET para a URL especificada.
     *
     * @param  string  $url  URL para realizar a requisição.
     * @return array Resposta decodificada JSON da requisição.
     *
     * @throws Exception Em caso de falha após tentativas de retry.
     */
    public function get(string $url): array
    {
        return $this->makeRequest('get', $url);
    }

    /**
     * Faz uma requisição HTTP POST para a URL especificada com corpo JSON.
     *
     * @param  string  $url  URL para realizar a requisição.
     * @param  array  $body  Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON da requisição.
     *
     * @throws Exception Em caso de falha após tentativas de retry.
     */
    public function post(string $url, array $body = []): array
    {
        return $this->makeRequest('post', $url, $body);
    }

    /**
     * Faz uma requisição HTTP PUT para a URL especificada com corpo JSON.
     *
     * @param  string  $url  URL para realizar a requisição.
     * @param  array  $body  Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON da requisição.
     *
     * @throws Exception Em caso de falha após tentativas de retry.
     */
    public function put(string $url, array $body = []): array
    {
        return $this->makeRequest('put', $url, $body);
    }

    /**
     * Faz uma requisição HTTP DELETE para a URL especificada com corpo JSON.
     *
     * @param  string  $url  URL para realizar a requisição.
     * @param  array  $body  Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON da requisição.
     *
     * @throws Exception Em caso de falha após tentativas de retry.
     */
    public function delete(string $url, array $body = []): array
    {
        return $this->makeRequest('delete', $url, $body);
    }

    /**
     * Realiza a requisição HTTP usando retry nativo do Laravel HTTP Client.
     *
     * @param  string  $method  Metodo HTTP (get, post, put, delete).
     * @param  string  $url  URL da requisição.
     * @param  array  $body  Corpo da requisição (opcional).
     * @return array Resposta decodificada JSON da requisição.
     *
     * @throws Exception Se a requisição falhar após todas as tentativas.
     */
    protected function makeRequest(string $method, string $url, array $body = []): array
    {
        try {
            $response = Http::acceptJson()
                ->retry($this->retries, $this->retryDelayMs)
                ->{$method}($url, $body);

            if ($response->status() == Response::HTTP_NO_CONTENT) {
                return [];
            }

            return $response->json();

        } catch (RequestException $e) {
            $this->logger->error("HttpService: Error {$method} in {$url} - ".$e->getMessage());
            throw new UnauthorizedTransferException('An error occurred during the request to authorization service.');
        }
    }
}
