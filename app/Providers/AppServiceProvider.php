<?php

namespace App\Providers;

use App\Enums\PermissionsEnum;
use App\Exceptions\HandlerException;
use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, HandlerException::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('hasPermission', function (?User $guest, User $user, PermissionsEnum $permissions): bool {
            return in_array($permissions->value,
                $user->role->permissions->pluck('name')->toArray()
            );
        });
    }
}
