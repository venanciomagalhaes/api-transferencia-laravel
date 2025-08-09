<?php

namespace App\Modules\User\V1\Repositories;

use App\Modules\User\V1\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUserByDocumentOrEmail(string $document, string $email): ?User
    {
        return $this->model->where('cpf_cnpj', $document)
            ->orWhere('email', $email)
            ->first();
    }

    public function create(array $data): User
    {
        $user = $this->model->create($data);
        return $user->load('type.permissions');
    }

}
