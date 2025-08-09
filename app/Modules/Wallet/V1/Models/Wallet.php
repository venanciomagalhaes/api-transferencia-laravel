<?php

namespace App\Modules\Wallet\V1\Models;

use App\Modules\User\V1\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $table = 'wallet';

    protected $fillable = [
        'uuid',
        'amount',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
