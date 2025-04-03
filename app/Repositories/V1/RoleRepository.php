<?php

namespace App\Repositories\V1;


use App\Models\Role;

class RoleRepository
{

    public function findByName(string $name): Role
    {
        return  Role::where('name', $name)->firstOrFail();
    }

}
