<?php

namespace App\Services\V1;

use App\Exceptions\Authorization\UnauthorizedTransferException;
use App\Helpers\HttpClientHelper;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationService
{
    private static string $URL = 'https://util.devi.tools/api/v2/authorize';

    public function verify(): array
    {
        try {
            $response = HttpClientHelper::get(static::$URL);
            if ($response->failed() || ! $response->json()['data']['authorization']) {
                abort(Response::HTTP_UNAUTHORIZED, 'Transfer not authorized.');
            }

            return $response->json();
        } catch (\Exception $e) {
            throw new UnauthorizedTransferException(
                'Transfer not authorized.',
                Response::HTTP_UNAUTHORIZED,
            );
        }
    }
}
