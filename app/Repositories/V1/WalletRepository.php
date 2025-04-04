<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Models\Wallet;

class WalletRepository
{
    public function createDefaultWallet(array $data): Wallet
    {
        return Wallet::create($data);
    }

    public function incrementAmount(User $payee, float $transferValue): User
    {
        $payee->wallet->amount += $transferValue;
        $payee->wallet->save();
        return $payee;
    }

    public function decrementAmount(User $payer, float $transferValue): User
    {
        $payer->wallet->amount -= $transferValue;
        $payer->wallet->save();
        return $payer;
    }
}
