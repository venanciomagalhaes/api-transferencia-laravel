<?php

namespace App\Repositories\V1;

use App\Models\Wallet;

class WalletRepository
{
    public function createDefaultWallet(array $data): Wallet
    {
        return Wallet::create($data);
    }
}
