<?php

namespace App\Services\V1;

use App\Exceptions\Notification\FailedNotificationException;
use App\Helpers\HttpClientHelper;
use Symfony\Component\HttpFoundation\Response;

class NotificationService
{
    private static string $URL = 'https://util.devi.tools/api/v1/notify';

    public function notify()
    {
        try{
            $response = HttpClientHelper::post(static::$URL);

            if ($response->failed()) {
                abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to send notification.');
            }

            return $response->json();
        }catch (\Exception $exception){
            throw new FailedNotificationException(
                'Failed to send notification.',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
