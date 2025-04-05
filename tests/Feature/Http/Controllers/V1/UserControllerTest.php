<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Enums\RolesEnum;
use App\Helpers\UuidHelper;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\V1\RoleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

test('GET /api/v1/users returns 200 status and correct structure when users exist', function () {
    $role = (new RoleRepository)->findByName(RolesEnum::CUSTOMER->name);

    $user = User::factory()->withRole($role)->create();

    Wallet::create([
        'uuid' => UuidHelper::generate(),
        'user_id' => $user->id,
        'amount' => 1000,
    ]);

    $response = $this->getJson('/api/v1/users');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'role',
                    'email',
                    'cpf_cnpj',
                    'permissions' => [
                        '*' => ['name', 'description'],
                    ],
                    'wallet_amount',
                    '__links' => ['self', 'index'],
                ],
            ],
            'pagination',
        ]);
});

test('GET /api/v1/users returns 204 when no users exist', function () {
    $response = $this->getJson('/api/v1/users');

    $response->assertStatus(Response::HTTP_NO_CONTENT);
});

test('POST /api/v1/users successfully creates a user', function () {
    $role = (new RoleRepository)->findByName(RolesEnum::CUSTOMER->name);

    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '12202040641',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => $role->name,
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'data' => [
                'uuid',
                'name',
                'role',
                'email',
                'cpf_cnpj',
                'permissions' => [
                    '*' => ['name', 'description'],
                ],
                'wallet_amount',
                '__links' => ['self', 'index'],
            ],
        ]);

    $this->assertDatabaseHas('users', ['email' => $userData['email']]);
});

test('POST /api/v1/users fails with invalid role', function () {
    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => fake()->unique()->numerify('###########'),
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => 'invalid_role',
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['role']);
});

test('GET /api/v1/users/{uuid} returns a specific user', function () {
    $role = (new RoleRepository)->findByName(RolesEnum::CUSTOMER->name);

    $user = User::factory()->withRole($role)->create();

    Wallet::create([
        'uuid' => \App\Helpers\UuidHelper::generate(),
        'user_id' => $user->id,
        'amount' => 1000,
    ]);

    $response = $this->getJson("/api/v1/users/{$user->uuid}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'message',
            'data' => [
                'uuid',
                'name',
                'role',
                'email',
                'cpf_cnpj',
                'permissions' => [
                    '*' => ['name', 'description'],
                ],
                'wallet_amount',
                '__links' => ['self', 'index'],
            ],
        ]);
});

test('GET /api/v1/users/{uuid} returns 404 for a non-existent user', function () {
    $response = $this->getJson('/api/v1/users/fake-uuid');

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('POST /api/v1/users fails with duplicate email', function () {
    $role = (new RoleRepository)->findByName(RolesEnum::CUSTOMER->name);

    $email = 'duplicate@example.com';

    User::factory()->withRole($role)->create([
        'email' => $email,
    ]);

    $userData = [
        'name' => fake()->name(),
        'email' => $email, // E-mail duplicado
        'cpf_cnpj' => fake()->unique()->numerify('###########'),
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => $role->name,
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

test('POST /api/v1/users fails with duplicate CPF', function () {
    $role = (new RoleRepository)->findByName(RolesEnum::CUSTOMER->name);

    $cpf = '52998224725';

    User::factory()->withRole($role)->create([
        'cpf_cnpj' => $cpf,
    ]);

    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => $cpf, // CPF duplicado
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => $role->name,
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['cpf_cnpj']);
});
