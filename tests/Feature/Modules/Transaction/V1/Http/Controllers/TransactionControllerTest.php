<?php

namespace Tests\Feature\Modules\Transaction\V1\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

$endpoint = '/api/v1/transfer';

beforeEach(function () {
    $endpointUser = '/api/v1/auth/user';

    $password = Str::password();

    $usuarioCommon1 = [
        'name' => 'Lucas Silva Pereira',
        'document' => '98765432100',
        'email' => 'lucas.silva@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ];

    $usuarioPJ = [
        'name' => 'Tech Solutions S.A.',
        'document' => '11222333000181',
        'email' => 'contato@techsolutions.com.br',
        'password' => $password,
        'password_confirmation' => $password,
    ];

    $usuarioCommon2 = [
        'name' => 'Mariana Costa Oliveira',
        'document' => '12345678909',
        'email' => 'mariana.costa@example.com',
        'password' => $password,
        'password_confirmation' => $password,
    ];

    $usuarioCommon1Response = $this->postJson($endpointUser, $usuarioCommon1);
    $usuarioPjResponse = $this->postJson($endpointUser, $usuarioPJ);
    $usuarioCommon2Response = $this->postJson($endpointUser, $usuarioCommon2);

    $usuarioCommon1Response->assertStatus(201);
    $usuarioPjResponse->assertStatus(201);
    $usuarioCommon2Response->assertStatus(201);

    $this->uuidUsuarioCommon1 = $usuarioCommon1Response->json('data')['uuid'];
    $this->uuidUsuarioCommon2 = $usuarioCommon2Response->json('data')['uuid'];
    $this->uuidUsuarioPj = $usuarioPjResponse->json('data')['uuid'];
});

test('Espero que o payer e payee somente aceitem uuid existentes', function () use ($endpoint) {
    $response = $this->postJson($endpoint, [
        'payer' => Str::uuid()->toString(),
        'payee' => Str::uuid()->toString(),
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

test('Espero que o valor da transação precise ser maior que zero', function () use ($endpoint) {
    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon1,
        'payee' => $this->uuidUsuarioPj,
        'value' => -100.00,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

test('Espero que o payee e payer precisem ser pessoas diferentes', function () use ($endpoint) {
    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon2,
        'payee' => $this->uuidUsuarioCommon2,
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_BAD_REQUEST);
});

test('Espero que o somente o usuário common possa transferir', function () use ($endpoint) {
    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioPj,
        'payee' => $this->uuidUsuarioCommon2,
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});

test('Espero que o usuário common possa transferir para outro common e para merchant', function () use ($endpoint) {

    Http::fake([
        env('AUTHORIZE_TRANSACTION_ENDPOINT_URL') => Http::response([
            'status' => true,
            'data' => ['authorization' => true],
        ], 200)]);

    Http::fake([
        env('NOTIFICATION_SERVICE_ENDPOINT_URL') => Http::response([
            'status' => true,
            'data' => ['authorization' => true],
        ], 200)]);

    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon1,
        'payee' => $this->uuidUsuarioCommon2,
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_OK);

    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon1,
        'payee' => $this->uuidUsuarioPj,
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_OK);
});

test('Espero que o payer precise ter saldo suficiente', function () use ($endpoint) {

    Http::fake([
        env('AUTHORIZE_TRANSACTION_ENDPOINT_URL') => Http::response([
            'status' => true,
            'data' => ['authorization' => true],
        ], 200)]);

    Http::fake([
        env('NOTIFICATION_SERVICE_ENDPOINT_URL') => Http::response([
            'status' => true,
            'data' => ['authorization' => true],
        ], 200)]);

    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon1,
        'payee' => $this->uuidUsuarioCommon2,
        'value' => 10000.00,
    ]);

    $response->assertStatus(Response::HTTP_BAD_REQUEST);
});

test('Espero que falta de autorização impeça a transferência', function () use ($endpoint) {

    Http::fake([
        env('NOTIFICATION_SERVICE_ENDPOINT_URL') => Http::response([
            'status' => true,
            'data' => ['authorization' => true],
        ], 200)]);

    Http::fake([
        env('AUTHORIZE_TRANSACTION_ENDPOINT_URL') => Http::response([
            'status' => false,
            'data' => ['authorization' => false],
        ], 200)]);

    $response = $this->postJson($endpoint, [
        'payer' => $this->uuidUsuarioCommon1,
        'payee' => $this->uuidUsuarioCommon2,
        'value' => 100.00,
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});
