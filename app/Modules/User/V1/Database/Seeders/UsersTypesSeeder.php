<?php

namespace App\Modules\User\V1\Database\Seeders;

use App\Modules\Permissions\V1\Enums\PermissionsNameEnum;
use App\Modules\Permissions\V1\Models\Permission;
use App\Modules\Permissions\V1\Models\UserTypePermission;
use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Models\UserType;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UsersTypesSeeder extends Seeder
{
    public function __construct(
        private readonly UserType $userTypeModel,
        private readonly Permission $permissionModel,
        private readonly UserTypePermission $userTypePermissionModel,
    ) {}

    public function run(): void
    {
        $userTypes = $this->createUserTypes();
        $permissions = $this->createPermissions();

        $this->assignPermissionsToUserTypes($userTypes, $permissions);
    }

    private function createUserTypes(): array
    {
        $commonUser = $this->userTypeModel->firstOrCreate(
            ['name' => UserTypeNameEnum::COMMON->value],
            ['uuid' => Uuid::uuid4()->toString()]
        );

        $merchantUser = $this->userTypeModel->firstOrCreate(
            ['name' => UserTypeNameEnum::MERCHANT->value],
            ['uuid' => Uuid::uuid4()->toString()]
        );

        return [
            'common' => $commonUser,
            'merchant' => $merchantUser,
        ];
    }

    private function createPermissions(): array
    {
        $sendTransaction = $this->permissionModel->firstOrCreate(
            ['name' => PermissionsNameEnum::SEND_TRANSACTION->value],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'description' => 'Permission to send transactions',
            ]
        );

        $receiveTransaction = $this->permissionModel->firstOrCreate(
            ['name' => PermissionsNameEnum::RECEIVE_TRANSACTION->value],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'description' => 'Permission to receive transactions',
            ]
        );

        return [
            'send' => $sendTransaction,
            'receive' => $receiveTransaction,
        ];
    }

    private function assignPermissionsToUserTypes(array $userTypes, array $permissions): void
    {
        $this->attachPermission($userTypes['common']->id, $permissions['send']->id);
        $this->attachPermission($userTypes['common']->id, $permissions['receive']->id);

        $this->attachPermission($userTypes['merchant']->id, $permissions['receive']->id);
    }

    private function attachPermission(int $userTypeId, int $permissionId): void
    {
        $this->userTypePermissionModel->firstOrCreate(
            [
                'user_type_id' => $userTypeId,
                'permission_id' => $permissionId,
            ],
            [
                'uuid' => Uuid::uuid4()->toString(),
            ]
        );
    }
}
