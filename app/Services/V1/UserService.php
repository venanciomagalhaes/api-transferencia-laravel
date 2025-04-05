<?php

namespace App\Services\V1;

use App\Dtos\V1\User\UserStoreDto;
use App\Mappers\V1\UserMapper;
use App\Mappers\V1\WalletMapper;
use App\Models\User;
use App\Repositories\V1\RoleRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WalletRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository,
        private WalletRepository $walletRepository,
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
            $user = $this->userRepository->create($data);
            $this->walletRepository->createDefaultWallet(WalletMapper::toArrayForStore($user->id));

            return $user->load('wallet');
        });

    }

    public function show(string $uuid): User
    {
        return $this->userRepository->findByUuid($uuid);
    }
}
