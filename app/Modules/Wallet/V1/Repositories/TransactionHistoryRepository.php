<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\Wallet\V1\Models\TransactionHistory;

class TransactionHistoryRepository implements TransactionHistoryRepositoryInterface
{
    public function __construct(
        private TransactionHistory $model
    )
    {
    }

    public function create(array $data): TransactionHistory
    {
        return  $this->model->create($data);
    }
}
