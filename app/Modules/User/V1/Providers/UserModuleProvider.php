<?php

namespace App\Modules\User\V1\Providers;

use App\Modules\User\V1\Repositories\UserRepository;
use App\Modules\User\V1\Repositories\UserRepositoryInterface;
use App\Modules\User\V1\Repositories\UserTypeRepository;
use App\Modules\User\V1\Repositories\UserTypeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserModuleProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserTypeRepositoryInterface::class, UserTypeRepository::class);
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
