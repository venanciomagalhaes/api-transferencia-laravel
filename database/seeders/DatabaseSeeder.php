<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function __construct(
        private readonly RolePermissionSeeder $rolePermissionSeeder,
    ) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->rolePermissionSeeder->run();
    }
}
