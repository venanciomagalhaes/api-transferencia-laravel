<?php

namespace App\Modules\Permissions\V1\Models;

use App\Modules\User\V1\Models\UserType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'uuid',
        'name',
        'description',
    ];

    public function usersType(): BelongsToMany
    {
        return $this->belongsToMany(
            UserType::class,
            'user_type_permissions',
            'permission_id',
            'user_type_id'
        );
    }
}
