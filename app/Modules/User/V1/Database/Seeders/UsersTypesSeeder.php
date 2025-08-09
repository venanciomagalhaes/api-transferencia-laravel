<?php

namespace App\Modules\User\V1\Database\Seeders;

use App\Modules\User\V1\Enums\UserTypeNameEnum;
use App\Modules\User\V1\Models\UserType;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UsersTypesSeeder extends Seeder
{
    public function __construct(
        private readonly UserType $model
    ) {}

    public function run(): void
    {
        $this->model->firstOrCreate(
            ['name' => UserTypeNameEnum::COMMON->value],
            [
                'uuid' => Uuid::uuid4()->toString(),
            ]
        );

        $this->model->firstOrCreate(
            ['name' => UserTypeNameEnum::MERCHANT->value],
            [
                'uuid' => Uuid::uuid4()->toString(),
            ]
        );
    }
}
