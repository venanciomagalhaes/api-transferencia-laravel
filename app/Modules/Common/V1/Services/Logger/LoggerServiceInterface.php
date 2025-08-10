<?php

namespace App\Modules\Common\V1\Services\Logger;

/**
 * Interface que define os métodos para um serviço de registro de logs.
 */
interface LoggerServiceInterface
{
    /**
     * Registra uma mensagem de nível informativo (info).
     *
     * @param string $message Mensagem a ser registrada no log.
     * @param array $context Informações adicionais de contexto (opcional).
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Registra uma mensagem de nível aviso (warning).
     *
     * @param string $message Mensagem a ser registrada no log.
     * @param array $context Informações adicionais de contexto (opcional).
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Registra uma mensagem de nível erro (error).
     *
     * @param string $message Mensagem a ser registrada no log.
     * @param array $context Informações adicionais de contexto (opcional).
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * Registra uma mensagem de nível debug.
     *
     * @param string $message Mensagem a ser registrada no log.
     * @param array $context Informações adicionais de contexto (opcional).
     * @return void
     */
    public function debug(string $message, array $context = []): void;
}
