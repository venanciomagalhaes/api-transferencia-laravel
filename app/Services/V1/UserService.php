<?php

namespace App\Services\V1;

use App\Dtos\V1\User\UserStoreDto;
use App\Mappers\V1\UserMapper;
use App\Models\User;
use App\Repositories\V1\RoleRepository;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository,
    ) {}

    public function index(): LengthAwarePaginator
    {
        return $this->userRepository->getAllPaginated();
    }

    public function store(UserStoreDto $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $role = $this->roleRepository->findByName($dto->getRoleName());
            $dto->setRoleId($role->id);
            $data = UserMapper::toArrayFromDto($dto);
            return $this->userRepository->create($data);
        });

    }

    public function show(string $uuid): User
    {
        return $this->userRepository->findByUuid($uuid);
    }
}
