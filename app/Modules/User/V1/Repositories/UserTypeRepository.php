<?php

namespace App\Modules\User\V1\Repositories;

use App\Modules\Common\V1\Services\Cache\CacheServiceInterface;
use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Models\UserType;

readonly class UserTypeRepository implements UserTypeRepositoryInterface
{
    public function __construct(
        private UserType $model,
        private readonly CacheServiceInterface $cacheService
    ) {}

    public function getUserTypeByName(UserTypeNameEnum $userTypeName): UserType
    {
        $cacheKey = 'user-type-'.$userTypeName->value;

        return $this->cacheService->remember(
            key: $cacheKey,
            callback: function () use ($userTypeName) {
                return $this->model->where('name', $userTypeName->value)->firstOrFail();
            },
            ttl: 3600
        );
    }
}
