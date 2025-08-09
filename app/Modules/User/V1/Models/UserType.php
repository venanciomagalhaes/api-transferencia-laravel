<?php

namespace App\Modules\User\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{

    protected $table = 'users_types';


    protected $fillable = [
        'id',
        'uuid',
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_type_id', 'id');
    }


}
