<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\Wallet\V1\Models\TransactionHistory;

interface TransactionHistoryRepositoryInterface
{
    public function create(array $data): TransactionHistory;
}
