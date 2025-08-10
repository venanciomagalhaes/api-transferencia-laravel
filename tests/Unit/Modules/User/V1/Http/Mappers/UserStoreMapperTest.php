<?php

namespace Tests\Unit\Modules\User\V1\Http\Mappers;

use App\Modules\User\V1\Http\Dtos\UserStoreDto;
use App\Modules\User\V1\Http\Mappers\UserStoreMapper;
use App\Modules\User\V1\Http\Requests\UserStoreRequest;
use App\Modules\User\V1\Http\Resources\UserStoreResource;
use App\Modules\User\V1\Models\User;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Ramsey\Uuid\Uuid;

beforeEach(function () {
    $this->mapper = new UserStoreMapper;
});

it('converte um UserStoreRequest mockado em um UserStoreDto corretamente', function () {
    $request = Mockery::mock(UserStoreRequest::class);
    $request->shouldReceive('input')->with('name')->andReturn('Maria');
    $request->shouldReceive('input')->with('document')->andReturn('12345678900');
    $request->shouldReceive('input')->with('email')->andReturn('maria@example.com');
    $request->shouldReceive('input')->with('password')->andReturn('secret123');

    $dto = $this->mapper->fromRequestToDto($request);

    expect($dto->getName())->toBe('Maria')
        ->and($dto->getDocument())->toBe('12345678900')
        ->and($dto->getEmail())->toBe('maria@example.com')
        ->and(Hash::check('secret123', $dto->getPassword()))->toBeTrue();
});

it('converte um UserStoreDto mockado para array de persistência', function () {
    $dto = Mockery::mock(UserStoreDto::class);
    $dto->shouldReceive('getName')->andReturn('João');
    $dto->shouldReceive('getDocument')->andReturn('98765432100');
    $dto->shouldReceive('getEmail')->andReturn('joao@example.com');
    $dto->shouldReceive('getPassword')->andReturn('senha123');
    $dto->shouldReceive('getUserType')->andReturn(5);

    $data = $this->mapper->fromDtoToPersistency($dto);

    expect($data['name'])->toBe('João')
        ->and($data['cpf_cnpj'])->toBe('98765432100')
        ->and($data['email'])->toBe('joao@example.com')
        ->and($data['password'])->toBe('senha123')
        ->and($data['user_type_id'])->toBe(5)
        ->and(Uuid::isValid($data['uuid']))->toBeTrue();
});

it('transforma o model User em um array com resource usando mock', function () {
    $user = Mockery::mock(User::class);
    $resource = $this->mapper->fromModelToResource($user);

    expect($resource)
        ->toHaveKey('message', 'User created successfully.')
        ->and($resource['data'])->toBeInstanceOf(UserStoreResource::class);
});
