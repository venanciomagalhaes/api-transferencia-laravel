<?php

namespace App\Repositories\V1;

use App\Models\Transfer;

class TransferRepository
{
    public function create(array $data): Transfer
    {
        $transfer = Transfer::create($data);
        return $transfer->load('payer', 'payee');
    }
}
