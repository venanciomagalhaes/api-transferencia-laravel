<?php

namespace App\Modules\User\V1\Http\Mappers;

use App\Modules\User\V1\Http\Dtos\UserStoreDto;
use App\Modules\User\V1\Http\Requests\UserStoreRequest;
use App\Modules\User\V1\Http\Resources\UserStoreResource;
use App\Modules\User\V1\Models\User;
use Ramsey\Uuid\Uuid;

class UserStoreMapper
{
    public function fromRequestToDto(UserStoreRequest $request): UserStoreDto
    {
        return new UserStoreDto(
            name: $request->input('name'),
            document: $request->input('document'),
            email: $request->input('email'),
            password: $request->input('password'),
        );
    }

    public function fromDtoToPersistency(UserStoreDto $dto): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $dto->getName(),
            'cpf_cnpj' => $dto->getDocument(),
            'email' => $dto->getEmail(),
            'password' => $dto->getPassword(),
            'user_type_id' => $dto->getUserType(),
        ];
    }

    public function fromModelToResource(User $user): array
    {
        return [
            'message' => 'User created successfully.',
            'data' => new UserStoreResource($user)
        ];
    }
}
