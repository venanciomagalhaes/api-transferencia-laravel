<?php

namespace App\Modules\User\V1\Repositories;

use App\Modules\User\V1\Models\User;

interface UserRepositoryInterface
{
    public function getUserByDocumentOrEmail(string $document, string $email): ?User;

    public function create(array $data): User;

    public function findByUuid(string $uuid): User;

    public function findByUuidWithWalletLockForUpdate(string $uuid): User;
}
