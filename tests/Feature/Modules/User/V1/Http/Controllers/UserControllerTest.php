<?php

namespace Tests\Feature\Modules\User\V1\Http\Controllers;

use App\Modules\User\V1\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


$endpoint = '/api/v1/auth/user';

test('deve criar um novo usuário com sucesso e com carteira de 1000.00', function () use ($endpoint) {
    $password = Str::password();
    $cpf = '41590444094';

    $response = $this->postJson($endpoint, [
        'name' => 'João Silva',
        'document' => $cpf,
        'email' => 'joao@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(Response::HTTP_CREATED);

    $response->assertJsonStructure([
        'message',
        'data' => [
            'uuid',
            'name',
            'type',
            'document',
            'email',
            'amount',
        ]
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'joao@example.com',
        'cpf_cnpj' => $cpf,
    ]);

    $user = User::where('email', 'joao@example.com')->first();

    expect($user->wallet->amount)->toBe('1000.00');
});

test('deve impedir criação de usuário com email duplicado', function () use ($endpoint) {
    $password = Str::password();
    $cpf1 = '41590444094';
    $cpf2 = '56028059072';

    // Primeiro usuário
    $this->postJson($endpoint, [
        'name' => 'João Silva',
        'document' => $cpf1,
        'email' => 'duplicado@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ])->assertCreated();

    $response = $this->postJson($endpoint, [
        'name' => 'Maria Silva',
        'document' => $cpf2,
        'email' => 'duplicado@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(Response::HTTP_CONFLICT)
        ->assertJson(['message' => "This user already exists"]);
});

test('deve impedir criação de usuário com documento duplicado', function () use ($endpoint) {
    $password = Str::password();
    $cpf = '41590444094';

    $this->postJson($endpoint, [
        'name' => 'João Silva',
        'document' => $cpf,
        'email' => Str::random(10) . '@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ])->assertCreated();

    $response = $this->postJson($endpoint, [
        'name' => 'Maria Silva',
        'document' => $cpf,
        'email' => Str::random(10) . '@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(Response::HTTP_CONFLICT)
        ->assertJson(['message' => "This user already exists"]);
});

test('deve impedir criação de usuário com CPF inválido', function () {
    $password = Str::password();

    $response = $this->postJson('/api/v1/auth/user', [
        'name' => 'João CPF Inválido',
        'document' => '12345678900',
        'email' => 'cpf_invalido@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJson([
        'message' => 'This document is invalid',
    ]);
});

test('deve impedir criação de usuário com CNPJ inválido', function () {
    $password = Str::password();

    $response = $this->postJson('/api/v1/auth/user', [
        'name' => 'Maria CNPJ Inválido',
        'document' => '12345678000100',
        'email' => 'cnpj_invalido@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJson([
        'message' => 'This document is invalid',
    ]);
});
