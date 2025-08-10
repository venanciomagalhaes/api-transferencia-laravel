<?php

namespace App\Modules\Permissions\V1\Providers;

use Carbon\Laravel\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
