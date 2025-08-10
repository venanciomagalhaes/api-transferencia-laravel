<?php

namespace Tests\Unit\Modules\User\V1\Http\Dtos;

use App\Modules\User\V1\Http\Dtos\UserStoreDto;
use Illuminate\Support\Facades\Hash;

it('cria o DTO corretamente e retorna os valores esperados', function () {
    $dto = new UserStoreDto(
        name: 'Maria da Silva',
        document: '12345678900',
        email: 'maria@example.com',
        password: 'senha123'
    );

    expect($dto->getName())->toBe('Maria da Silva')
        ->and($dto->getDocument())->toBe('12345678900')
        ->and($dto->getEmail())->toBe('maria@example.com')
        ->and(Hash::check('senha123', $dto->getPassword()))->toBeTrue()
        ->and($dto->getUserType())->toBeNull();
});

it('permite definir e recuperar o userType', function () {
    $dto = new UserStoreDto(
        name: 'Carlos Oliveira',
        document: '98765432100',
        email: 'carlos@example.com',
        password: 'pass123'
    );

    $dto->setUserType(2);

    expect($dto->getUserType())->toBe(2);
});
