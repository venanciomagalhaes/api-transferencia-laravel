<?php

namespace Tests\Unit\Dtos\V1\User;


use App\Dtos\V1\User\UserStoreDto;
use App\Enums\RolesEnum;

test('creates UserStoreDto with valid data', function () {
    $name = 'Tony Stark';
    $email = 'ironman@starkindustries.com';
    $cpfCnpj = '123.456.789-00';
    $password = 'iamironman';
    $roleName = RolesEnum::CUSTOMER->value;
    $passwordConfirmation = 'iamironman';

    $dto = new UserStoreDto(
        name: $name,
        email: $email,
        cpf_cnpj: $cpfCnpj,
        password: $password,
        roleName: $roleName,
        password_confirmation: $passwordConfirmation
    );

    expect($dto->getName())->toBe($name)
        ->and($dto->getEmail())->toBe($email)
        ->and($dto->getCpfCnpj())->toBe(preg_replace('/\D/', '', $cpfCnpj))
        ->and($dto->getPassword())->toBe($password)
        ->and($dto->getRoleName())->toBe($roleName)
        ->and($dto->getPasswordConfirmation())->toBe($passwordConfirmation);
});



test('sets and gets role ID', function () {
    $dto = new UserStoreDto(
        name: 'Peter Parker',
        email: 'spiderman@dailybugle.com',
        cpf_cnpj: '321.654.987-00',
        password: 'withgreatpower',
        roleName: RolesEnum::MERCHANT->value,
        password_confirmation: 'withgreatpower'
    );

    $dto->setRoleId(5);
    expect($dto->getRoleId())->toBe(5);
});
