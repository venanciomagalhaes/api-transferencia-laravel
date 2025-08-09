<?php

namespace App\Modules\Common\V1\Services\Logger;

interface LoggerServiceInterface
{
    public function info(string $message, array $context = []): void;

    public function warning(string $message, array $context = []): void;

    public function error(string $message, array $context = []): void;

    public function debug(string $message, array $context = []): void;

}
