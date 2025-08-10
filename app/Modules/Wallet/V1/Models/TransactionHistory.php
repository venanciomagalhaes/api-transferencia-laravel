<?php

namespace App\Modules\Wallet\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionHistory extends Model
{
    protected $table = 'transaction_history';

    protected $fillable = [
        'uuid',
        'payer_wallet_id',
        'payee_wallet_id',
        'amount',
    ];

    public function payerWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payer_wallet_id', 'id');
    }

    public function payeeWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id', 'id');
    }
}
