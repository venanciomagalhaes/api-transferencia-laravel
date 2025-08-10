<?php

namespace App\Modules\Wallet\V1\Providers;

use App\Modules\Wallet\V1\Repositories\TransactionHistoryRepository;
use App\Modules\Wallet\V1\Repositories\TransactionHistoryRepositoryInterface;
use App\Modules\Wallet\V1\Repositories\WalletRepository;
use App\Modules\Wallet\V1\Repositories\WalletRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class WalletModuleProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(TransactionHistoryRepositoryInterface::class, TransactionHistoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
