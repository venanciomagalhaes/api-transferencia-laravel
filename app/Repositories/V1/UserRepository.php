<?php

namespace App\Repositories\V1;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getAllPaginated(): LengthAwarePaginator
    {
        return User::with([
            'role.permissions',
            'wallet',
        ])->paginate();
    }

    public function create(array $data): User
    {
        $user = User::create($data);
        $user->load([
            'role.permissions',
            'wallet',
        ]);

        return $user;
    }

    public function findByUuid(string $uuid): User
    {
        return User::with([
            'role.permissions',
            'wallet',
        ])->where('uuid', $uuid)->firstOrFail();
    }
}
