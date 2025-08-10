<?php

use App\Modules\Permissions\V1\Models\Permission;
use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Http\Resources\UserStoreResource;
use App\Modules\User\V1\Models\User;
use App\Modules\User\V1\Models\UserType;
use App\Modules\Wallet\V1\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;

it('transforma o model mockado corretamente em um resource', function () {

    $mockType = Mockery::mock(UserType::class);
    $mockType->shouldReceive('getAttribute')->with('name')->andReturn(UserTypeNameEnum::COMMON->name);

    $mockWallet = Mockery::mock(Wallet::class);
    $mockWallet->shouldReceive('getAttribute')->with('amount')->andReturn(1000.50);

    $mockPermissions = new Collection([
        Mockery::mock(Permission::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('name')->andReturn('send_transaction');
            $mock->shouldReceive('getAttribute')->with('description')->andReturn(null);
        }),
        Mockery::mock(Permission::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('name')->andReturn('receive_transaction');
            $mock->shouldReceive('getAttribute')->with('description')->andReturn(null);
        }),
    ]);

    $mockUser = Mockery::mock(User::class);
    $mockUser->shouldReceive('getAttribute')->with('uuid')->andReturn('uuid-123');
    $mockUser->shouldReceive('getAttribute')->with('name')->andReturn('João da Silva');
    $mockUser->shouldReceive('getAttribute')->with('cpf_cnpj')->andReturn('12345678901');
    $mockUser->shouldReceive('getAttribute')->with('email')->andReturn('joao@example.com');
    $mockUser->shouldReceive('getAttribute')->with('type')->andReturn($mockType);
    $mockUser->shouldReceive('getAttribute')->with('wallet')->andReturn($mockWallet);
    $mockType->shouldReceive('getAttribute')->with('permissions')->andReturn($mockPermissions);

    $resource = (new UserStoreResource($mockUser))->toArray(Request::create('/'));

    expect($resource)->toBe([
        'uuid' => 'uuid-123',
        'name' => 'João da Silva',
        'type' => UserTypeNameEnum::COMMON->name,
        'document' => '12345678901',
        'email' => 'joao@example.com',
        'amount' => 1000.50,
        'permissions' => [
            [
                'name' => 'send_transaction',
                'description' => null,
            ],
            [
                'name' => 'receive_transaction',
                'description' => null,
            ],
        ],
    ]);
});
