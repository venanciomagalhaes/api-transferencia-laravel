<?php

namespace App\Services\V1;

use App\Helpers\HttpClientHelper;
use Symfony\Component\HttpFoundation\Response;

class NotificationService
{
    private static string $URL = 'https://util.devi.tools/api/v1/notify';

    public function notify()
    {
        $response = HttpClientHelper::post(static::$URL);

        if ($response->failed()) {
            abort(Response::HTTP_UNAUTHORIZED, 'Failed to send notification.');
        }

        return $response->json();
    }
}
