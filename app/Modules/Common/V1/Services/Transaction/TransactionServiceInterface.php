<?php

namespace App\Modules\Common\V1\Services\Transaction;

namespace App\Modules\Common\V1\Services\Transaction;

use Closure;

/**
 * Interface que define os métodos para um serviço de transações.
 */
interface TransactionServiceInterface
{
    /**
     * Executa uma operação dentro de uma transação.
     *
     * O callback passado será executado dentro do contexto da transação,
     * garantindo que as operações sejam atômicas.
     *
     * @param  Closure  $callback  Função callback que contém a lógica da transação.
     * @return mixed Retorna o resultado da execução do callback.
     */
    public function run(Closure $callback): mixed;
}
