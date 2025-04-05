<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Helpers\UuidHelper;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customerRole = $this->createCustomerRole();
        $merchantRole = $this->createMerchantRole();

        $makeATransferPermission = $this->createTransferPermission();
        $receiveATransferPermission = $this->createReceiveTransferPermission();

        $this->syncRolePermissionsToCustomer(
            $customerRole,
            $makeATransferPermission,
            $receiveATransferPermission
        );
        $this->syncMerchantRolePermissionToMerchant(
            $merchantRole,
            $receiveATransferPermission
        );

    }

    protected function createCustomerRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => RolesEnum::CUSTOMER->value],
            [
                'uuid' => UuidHelper::generate(),
                'name' => RolesEnum::CUSTOMER->value,
            ]);
    }

    protected function createTransferPermission(): Permission
    {
        return Permission::firstOrCreate([
            'name' => PermissionsEnum::MAKE_A_TRANSFER->value,
        ], [
            'uuid' => UuidHelper::generate(),
            'name' => PermissionsEnum::MAKE_A_TRANSFER->value,
            'description' => 'Allows the user to make a transfer',
        ]);
    }

    protected function createReceiveTransferPermission(): Permission
    {
        return Permission::firstOrCreate([
            'name' => PermissionsEnum::RECEIVE_A_TRANSFER->value,
        ], [
            'uuid' => UuidHelper::generate(),
            'name' => PermissionsEnum::RECEIVE_A_TRANSFER->value,
            'description' => 'Allows the user to receive a transfer',
        ]);
    }

    protected function createMerchantRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => RolesEnum::MERCHANT->value],
            [
                'uuid' => UuidHelper::generate(),
                'name' => RolesEnum::MERCHANT->value,
            ]);
    }

    protected function syncRolePermissionsToCustomer(
        Role $customerRole,
        Permission $makeATransferPermission,
        Permission $receiveATransferPermission
    ): void {
        $customerRole->permissions()->syncWithoutDetaching([
            $makeATransferPermission->id => ['uuid' => UuidHelper::generate()],
            $receiveATransferPermission->id => ['uuid' => UuidHelper::generate()],
        ]);
    }

    protected function syncMerchantRolePermissionToMerchant(
        Role $merchantRole,
        Permission $receiveATransferPermission): void
    {
        $merchantRole->permissions()->syncWithoutDetaching([
            $receiveATransferPermission->id => ['uuid' => UuidHelper::generate()],
        ]);
    }
}
