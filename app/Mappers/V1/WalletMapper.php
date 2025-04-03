<?php

namespace App\Mappers\V1;

use App\Dtos\V1\User\UserStoreDto;
use App\Helpers\PaginationHelper;
use App\Helpers\UuidHelper;
use App\Http\Requests\V1\User\UserStoreRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class WalletMapper
{

    public static function toArrayForStore(int $userId): array
    {
        return [
            'user_id' => $userId,
            'amount' => 1000.00,
            'uuid' => UuidHelper::generate(),
        ];
    }
}
