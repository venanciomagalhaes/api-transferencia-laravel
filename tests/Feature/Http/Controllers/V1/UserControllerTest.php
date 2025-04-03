<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\Enums\RolesEnum;
use App\Repositories\V1\RoleRepository;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Helpers\UuidHelper;

uses(RefreshDatabase::class);

test('GET /api/v1/users returns 200 status and correct structure when users exist', function () {
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();
    $role = (new RoleRepository())->findByName(RolesEnum::CUSTOMER->name);
    $user = User::create([
        'uuid' => UuidHelper::generate(),
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725',
        'password' => bcrypt('Password@123'),
        'role_id' => $role->id,
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
                    '__links' => [
                        'self',
                        'index',
                    ],
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
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();

    $role = (new RoleRepository())->findByName(RolesEnum::CUSTOMER->name);

    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725',
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
                '__links' => [
                    'self',
                    'index',
                ],
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
    ]);
});

test('POST /api/v1/users fails with invalid role', function () {
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();

    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => 'invalid_role',
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['role']);
});

test('GET /api/v1/users/{uuid} returns a specific user', function () {
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();

    $role = (new RoleRepository())->findByName(RolesEnum::CUSTOMER->name);

    $user = User::create([
        'uuid' => UuidHelper::generate(),
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725',
        'password' => bcrypt('Password@123'),
        'role_id' => $role->id,
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
                '__links' => [
                    'self',
                    'index',
                ],
            ],
        ]);
});

test('GET /api/v1/users/{uuid} returns 404 for a non-existent user', function () {
    $response = $this->getJson('/api/v1/users/fake-uuid');

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('POST /api/v1/users fails with duplicate email', function () {
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();

    $role = (new RoleRepository())->findByName(RolesEnum::CUSTOMER->name);

    User::create([
        'uuid' => UuidHelper::generate(),
        'name' => fake()->name(),
        'email' => 'duplicate@example.com',
        'cpf_cnpj' => '1234568909',
        'password' => bcrypt('Password@123'),
        'role_id' => $role->id,
    ]);

    $userData = [
        'name' => fake()->name(),
        'email' => 'duplicate@example.com', // E-mail duplicado
        'cpf_cnpj' => '12202040641',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => $role->name,
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

test('POST /api/v1/users fails with duplicate CPF', function () {
    $rolePermissionSeeder = new RolePermissionSeeder();
    $rolePermissionSeeder->run();

    $role = (new RoleRepository())->findByName(RolesEnum::CUSTOMER->name);

    User::create([
        'uuid' => UuidHelper::generate(),
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725',
        'password' => bcrypt('Password@123'),
        'role_id' => $role->id,
    ]);

    $userData = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'cpf_cnpj' => '52998224725', // CPF duplicado
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'role' => $role->name,
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['cpf_cnpj']);
});
