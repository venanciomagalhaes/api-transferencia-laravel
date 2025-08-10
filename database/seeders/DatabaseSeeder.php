<?php

namespace Database\Seeders;

use App\Modules\User\V1\Database\Seeders\UsersTypesSeeder;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTypesSeeder::class,
        ]);
    }
}
