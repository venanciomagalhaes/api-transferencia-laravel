<?php

namespace App\Modules\Common\V1\Services\Transaction;

use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionService implements TransactionServiceInterface
{
    /**
     * Executa um callback dentro de uma transação de banco de dados.
     *
     * @param Closure $callback
     * @return mixed
     * @throws Throwable
     */
    public function run(Closure $callback): mixed
    {
        return DB::transaction($callback);
    }
}
