<?php

namespace App\Modules\Transaction\V1\Providers;

use Illuminate\Support\ServiceProvider;

class TransactionModuleProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Http/Routes/api.php');
    }
}
