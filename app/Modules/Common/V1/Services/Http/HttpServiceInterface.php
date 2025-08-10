<?php

namespace App\Modules\Common\V1\Services\Http;

interface HttpServiceInterface
{
    public function get(string $url): array;

    public function post(string $url, array $body = []): array;

    public function put(string $url, array $body = []): array;

    public function delete(string $url, array $body = []): array;
}
