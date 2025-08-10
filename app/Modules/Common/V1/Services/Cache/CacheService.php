<?php

namespace App\Modules\Common\V1\Services\Cache;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    /**
     * Armazena um valor no cache por um tempo determinado.
     */
    public function put(string $key, mixed $value, int $ttl = 3600): void
    {
        Cache::put($key, $value, $ttl);
    }

    /**
     * Recupera um valor do cache, ou retorna o valor padrão.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * Verifica se uma chave existe no cache.
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * Remove uma chave do cache.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * Recupera um valor do cache ou armazena o resultado de uma Closure.
     */
    public function remember(string $key, Closure $callback, int $ttl = 3600): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }
}
