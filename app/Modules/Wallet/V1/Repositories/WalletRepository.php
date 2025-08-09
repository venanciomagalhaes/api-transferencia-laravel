<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\Wallet\V1\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{

    public function __construct(
        private Wallet $model
    )
    {
    }

    public function create(array $data): Wallet
    {
       return $this->model->create($data);
    }
}
