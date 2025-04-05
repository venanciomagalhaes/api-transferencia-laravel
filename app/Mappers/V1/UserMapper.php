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

class UserMapper
{
    public static function toResource(User $user): array
    {
        return [
            'message' => 'User created successfully.',
            'data' => new UserResource($user),
        ];
    }

    public static function toStoreDto(UserStoreRequest $request): UserStoreDto
    {
        return new UserStoreDto(
            name: $request->input('name'),
            email: $request->input('email'),
            cpf_cnpj: $request->input('cpf_cnpj'),
            password: Hash::make($request->input('password')),
            roleName: $request->input('role'),
            password_confirmation: Hash::make($request->input('password_confirmation'))
        );
    }

    public static function toCollectionResource(LengthAwarePaginator $users): array
    {
        return [
            'message' => 'Users listed successfully',
            'data' => UserResource::collection($users->items()),
            'pagination' => PaginationHelper::getPagination($users),
        ];
    }

    public static function toArrayFromDto(UserStoreDto $dto): array
    {
        return [
            'uuid' => UuidHelper::generate(),
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'cpf_cnpj' => $dto->getCpfCnpj(),
            'password' => $dto->getPassword(),
            'role_id' => $dto->getRoleId(),
        ];
    }

    public static function toDetailsResource(User $user): array
    {
        return [
            'message' => 'User retrieved  successfully.',
            'data' => new UserResource($user),
        ];
    }
}
