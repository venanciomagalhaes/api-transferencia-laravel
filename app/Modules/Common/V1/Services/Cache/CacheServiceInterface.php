<?php

namespace App\Modules\Common\V1\Services\Cache;

/**
 * Interface para serviços de cache.
 * Define os métodos para manipulação de cache, garantindo a implementação consistente.
 */
interface CacheServiceInterface
{
    /**
     * Armazena um valor no cache por um tempo determinado.
     *
     * @param  string  $key  Chave para armazenar o valor.
     * @param  mixed  $value  Valor a ser armazenado.
     * @param  int  $ttl  Tempo em segundos para manter o valor no cache (padrão 3600 segundos).
     */
    public function put(string $key, mixed $value, int $ttl = 3600): void;

    /**
     * Recupera um valor do cache, ou retorna o valor padrão caso a chave não exista.
     *
     * @param  string  $key  Chave do valor a ser recuperado.
     * @param  mixed|null  $default  Valor padrão retornado se a chave não existir (padrão null).
     * @return mixed Valor armazenado no cache ou valor padrão.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Verifica se uma chave existe no cache.
     *
     * @param  string  $key  Chave a ser verificada.
     * @return bool True se a chave existir, false caso contrário.
     */
    public function has(string $key): bool;

    /**
     * Remove uma chave do cache.
     *
     * @param  string  $key  Chave a ser removida.
     */
    public function forget(string $key): void;

    /**
     * Recupera um valor do cache ou, caso não exista,
     * executa uma Closure para obter o valor, armazena no cache e retorna o resultado.
     *
     * @param  string  $key  Chave para armazenar/recuperar o valor.
     * @param  \Closure  $callback  Função que será executada para obter o valor caso não exista no cache.
     * @param  int  $ttl  Tempo em segundos para manter o valor no cache (padrão 3600 segundos).
     * @return mixed Valor armazenado ou obtido pela Closure.
     */
    public function remember(string $key, \Closure $callback, int $ttl = 3600): mixed;
}
