<?php

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Helpers\UuidHelper;
use App\Models\User;
use App\Repositories\V1\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

function createUserWithWallet(RolesEnum $role, float $amount): User
{
    $role = (new RoleRepository)->findByName($role->name);
    $user = User::factory()->create(['role_id' => $role->id]);
    $user->wallet()->create(['uuid' => UuidHelper::generate(), 'amount' => $amount]);

    return $user;
}

beforeEach(function () {
    $this->roleCustomer = RolesEnum::CUSTOMER;
    $this->roleMerchant = RolesEnum::MERCHANT;
});

test('POST /api/v1/transfer should transfer successfully', function () {
    $payer = createUserWithWallet($this->roleCustomer, 1000);
    $payee = createUserWithWallet($this->roleMerchant, 0);

    Gate::shouldReceive('allows')
        ->withArgs(function ($permission, $params) {
            return $permission === 'hasPermission'
                && is_array($params)
                && $params[0] instanceof User
                && $params[1] instanceof PermissionsEnum;
        })
        ->andReturnTrue();

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
        'https://util.devi.tools/api/v1/notify' => Http::response(['message' => 'Notification sent'], 200),
    ]);

    $payload = [
        'value' => 10.8,
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
    ];

    $this->postJson('/api/v1/transfer', $payload)
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'message',
            'data' => [
                'payer',
                'payee',
                'amount',
            ],
        ]);
});

test('POST /api/v1/transfer fails if authorization fails', function () {
    $payer = createUserWithWallet($this->roleCustomer, 1000);
    $payee = createUserWithWallet($this->roleMerchant, 0);

    Gate::shouldReceive('allows')->andReturnTrue();

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => false]], 200),
    ]);

    $payload = [
        'value' => 10.8,
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
    ];

    $this->postJson('/api/v1/transfer', $payload)
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertSee('Transfer not authorized.');
});

test('POST /api/v1/transfer fails if notification fails', function () {
    $payer = createUserWithWallet($this->roleCustomer, 1000);
    $payee = createUserWithWallet($this->roleMerchant, 0);

    Gate::shouldReceive('allows')->andReturnTrue();

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
        'https://util.devi.tools/api/v1/notify' => Http::response(null, Response::HTTP_INTERNAL_SERVER_ERROR),
    ]);

    $payload = [
        'value' => 10.8,
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
    ];

    $this->postJson('/api/v1/transfer', $payload)
        ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->assertSee('Failed to send notification.');
});

test('POST /api/v1/transfer fails if payer has insufficient balance', function () {
    $payer = createUserWithWallet($this->roleCustomer, 5);
    $payee = createUserWithWallet($this->roleMerchant, 0);

    Gate::shouldReceive('allows')->andReturnTrue();

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
        'https://util.devi.tools/api/v1/notify' => Http::response(['message' => 'Notification sent'], 200),
    ]);

    $payload = [
        'value' => 10.8,
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
    ];

    $this->postJson('/api/v1/transfer', $payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertSee('The payer does not have sufficient balance.');
});

test('POST /api/v1/transfer fails if payer and payee are the same', function () {
    $payer = createUserWithWallet($this->roleCustomer, 100);

    Gate::shouldReceive('allows')->andReturnTrue();

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
        'https://util.devi.tools/api/v1/notify' => Http::response(['message' => 'Notification sent'], 200),
    ]);

    $payload = [
        'value' => 10.8,
        'payer' => $payer->uuid,
        'payee' => $payer->uuid,
    ];

    $this->postJson('/api/v1/transfer', $payload)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertSee('The payer and payee must be different users.');
});

test('POST /api/v1/transfer fails if required fields are missing', function () {
    $this->postJson('/api/v1/transfer', [])
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['payer', 'payee', 'value']);
});

test('POST /api/v1/transfer fails if payer or payee UUID is invalid', function () {
    $this->postJson('/api/v1/transfer', [
        'payer' => 'not-a-uuid',
        'payee' => 'not-a-uuid',
        'value' => 10,
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['payer', 'payee']);
});

test('POST /api/v1/transfer fails if value is zero or negative', function () {
    $payer = createUserWithWallet($this->roleCustomer, 100);
    $payee = createUserWithWallet($this->roleMerchant, 0);

    Gate::shouldReceive('allows')->andReturnTrue();
    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response(['data' => ['authorization' => true]], 200),
        'https://util.devi.tools/api/v1/notify' => Http::response(['message' => 'Notification sent'], 200),
    ]);

    $this->postJson('/api/v1/transfer', [
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
        'value' => 0,
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertSee('The value field must be at least 0.01.');

    $this->postJson('/api/v1/transfer', [
        'payer' => $payer->uuid,
        'payee' => $payee->uuid,
        'value' => -50,
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertSee('The value field must be at least 0.01.');
});
