<?php

namespace App\Modules\User\V1\Repositories;

use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Models\UserType;

interface UserTypeRepositoryInterface
{
    public function getUserTypeByName(UserTypeNameEnum $userTypeName): UserType;
}
