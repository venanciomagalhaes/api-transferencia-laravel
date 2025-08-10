<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\User\V1\Models\User;
use App\Modules\Wallet\V1\Models\Wallet;

interface WalletRepositoryInterface
{
    public function create(array $data): Wallet;

    public function makeTransfer(User $payer, float $value, User $payee): void;
}
