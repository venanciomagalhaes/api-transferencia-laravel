<?php

namespace App\Modules\User\V1\Models;

use App\Modules\Permissions\V1\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'user_type_permissions',
            'user_type_id',
            'permission_id',
        );
    }
}
