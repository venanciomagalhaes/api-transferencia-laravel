<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\Wallet\V1\Models\Wallet;

interface WalletRepositoryInterface
{
    public function create(array $data): Wallet;
}
