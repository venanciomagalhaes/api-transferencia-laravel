<?php

namespace App\Helpers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class HttpClientHelper
{
    private static int $maxRetries = 3;
    private static int $retryDelay = 200; // milissegundos

    private static function baseRequest(array $headers = []): PendingRequest
    {
        return Http::withHeaders(array_merge([
            'Accept' => 'application/json',
        ], $headers))->retry(self::$maxRetries, self::$retryDelay);
    }

    public static function get(string $url, array $query = [], array $headers = []): Response
    {
        return self::baseRequest($headers)->get($url, $query);
    }

    public static function post(string $url, array $data = [], array $headers = []): Response
    {
        return self::baseRequest($headers)->post($url, $data);
    }

    public static function put(string $url, array $data = [], array $headers = []): Response
    {
        return self::baseRequest($headers)->put($url, $data);
    }

    public static function delete(string $url, array $data = [], array $headers = []): Response
    {
        return self::baseRequest($headers)->delete($url, $data);
    }
}
