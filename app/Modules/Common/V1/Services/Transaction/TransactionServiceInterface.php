<?php

namespace App\Modules\Common\V1\Services\Transaction;

interface TransactionServiceInterface
{
    public function run(\Closure $callback): mixed;
}
