<?php

namespace App\Modules\Wallet\V1\Repositories;

use App\Modules\Transaction\V1\Http\Dtos\TransferDto;
use App\Modules\User\V1\Models\User;
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

    public function makeTransfer(User $payer, float $value, User $payee): void
    {
        $payer->wallet->amount -= $value;
        $payer->wallet->save();

        $payee->wallet->amount += $value;
        $payee->wallet->save();
    }
}
