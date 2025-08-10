<?php

namespace App\Modules\Common\V1\Services\Http;

namespace App\Modules\Common\V1\Services\Http;

/**
 * Interface para serviço HTTP, definindo métodos para requisições REST.
 */
interface HttpServiceInterface
{
    /**
     * Executa uma requisição HTTP GET para a URL especificada.
     *
     * @param string $url URL para a requisição.
     * @return array Resposta decodificada JSON.
     */
    public function get(string $url): array;

    /**
     * Executa uma requisição HTTP POST para a URL especificada com um corpo JSON.
     *
     * @param string $url URL para a requisição.
     * @param array $body Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON.
     */
    public function post(string $url, array $body = []): array;

    /**
     * Executa uma requisição HTTP PUT para a URL especificada com um corpo JSON.
     *
     * @param string $url URL para a requisição.
     * @param array $body Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON.
     */
    public function put(string $url, array $body = []): array;

    /**
     * Executa uma requisição HTTP DELETE para a URL especificada com um corpo JSON.
     *
     * @param string $url URL para a requisição.
     * @param array $body Dados a serem enviados no corpo da requisição.
     * @return array Resposta decodificada JSON.
     */
    public function delete(string $url, array $body = []): array;
}
