<?php

namespace App\Modules\Common\V1\Services\Cache;

interface CacheServiceInterface
{
    public function put(string $key, mixed $value, int $ttl = 3600): void;

    public function get(string $key, mixed $default = null): mixed;

    public function has(string $key): bool;

    public function forget(string $key): void;

    public function remember(string $key, \Closure $callback, int $ttl = 3600): mixed;
}
