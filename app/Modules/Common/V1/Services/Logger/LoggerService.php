<?php

namespace App\Modules\Common\V1\Services\Logger;

use Illuminate\Support\Facades\Log;

/**
 * Serviço responsável por abstrair o sistema de logging do Laravel.
 * Implementa métodos para registrar mensagens nos níveis info, warning, error e debug.
 */
class LoggerService implements LoggerServiceInterface
{
    /**
     * Registra uma mensagem de nível informativo (info).
     *
     * @param string $message Mensagem a ser registrada.
     * @param array $context Contexto adicional para a mensagem (opcional).
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    /**
     * Registra uma mensagem de nível aviso (warning).
     *
     * @param string $message Mensagem a ser registrada.
     * @param array $context Contexto adicional para a mensagem (opcional).
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    /**
     * Registra uma mensagem de nível erro (error).
     *
     * @param string $message Mensagem a ser registrada.
     * @param array $context Contexto adicional para a mensagem (opcional).
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }

    /**
     * Registra uma mensagem de nível debug.
     *
     * @param string $message Mensagem a ser registrada.
     * @param array $context Contexto adicional para a mensagem (opcional).
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        Log::debug($message, $context);
    }
}
