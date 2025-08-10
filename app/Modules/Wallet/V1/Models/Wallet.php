<?php

namespace App\Modules\Wallet\V1\Models;

use App\Modules\User\V1\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $table = 'wallet';

    protected $fillable = [
        'uuid',
        'amount',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sentTransactions(): HasMany
    {
        return $this->hasMany(TransactionHistory::class, 'payer_wallet_id', 'id');
    }

    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(TransactionHistory::class, 'payee_wallet_id', 'id');
    }
}
