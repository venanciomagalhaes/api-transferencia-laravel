<?php

namespace App\Services\V1;

use App\Helpers\HttpClientHelper;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationService
{
    private static string $URL = 'https://util.devi.tools/api/v2/authorize';

    public function verify(): array
    {
        $response = HttpClientHelper::get(static::$URL);

        if ($response->failed() || !$response->json()['data']['authorization']) {
            abort(Response::HTTP_UNAUTHORIZED, 'Transfer not authorized.');
        }

        return $response->json();
    }
}
