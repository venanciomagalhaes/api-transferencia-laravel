<?php

namespace App\Modules\Common\V1\Services\Cache;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Serviço de cache que abstrai as operações de armazenamento e recuperação
 * de dados em cache utilizando o cache do Laravel.
 *
 * Implementa a interface CacheServiceInterface para garantir a
 * consistência na utilização do cache na aplicação.
 */
class CacheService implements CacheServiceInterface
{
    /**
     * Armazena um valor no cache por um tempo determinado.
     *
     * @param  string  $key  Chave para armazenar o valor.
     * @param  mixed  $value  Valor a ser armazenado no cache.
     * @param  int  $ttl  Tempo em segundos que o valor ficará armazenado no cache (default 3600 segundos).
     */
    public function put(string $key, mixed $value, int $ttl = 3600): void
    {
        Cache::put($key, $value, $ttl);
    }

    /**
     * Recupera um valor do cache, ou retorna o valor padrão caso a chave não exista.
     *
     * @param  string  $key  Chave do valor a ser recuperado.
     * @param  mixed|null  $default  Valor padrão retornado se a chave não existir (default null).
     * @return mixed Valor armazenado no cache ou valor padrão.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * Verifica se uma chave existe no cache.
     *
     * @param  string  $key  Chave a ser verificada.
     * @return bool True se a chave existir no cache, false caso contrário.
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * Remove uma chave do cache.
     *
     * @param  string  $key  Chave a ser removida do cache.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Recupera um valor do cache ou, caso não exista,
     * executa uma Closure para obter o valor, armazena no cache e retorna o resultado.
     *
     * @param  string  $key  Chave para armazenar/recuperar o valor.
     * @param  Closure  $callback  Função que será executada para obter o valor caso não exista no cache.
     * @param  int  $ttl  Tempo em segundos que o valor ficará armazenado no cache (default 3600 segundos).
     * @return mixed Valor recuperado do cache ou retornado pela Closure.
     */
    public function remember(string $key, Closure $callback, int $ttl = 3600): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }
}
