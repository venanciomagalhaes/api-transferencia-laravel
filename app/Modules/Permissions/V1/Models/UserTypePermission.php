<?php

namespace App\Modules\Permissions\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\User\V1\Models\UserType;

class UserTypePermission extends Model
{
    protected $table = 'user_type_permissions';

    protected $fillable = [
        'uuid',
        'permission_id',
        'user_type_id',
    ];


    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
