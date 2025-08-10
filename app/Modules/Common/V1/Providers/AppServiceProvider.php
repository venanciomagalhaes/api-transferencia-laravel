<?php

namespace App\Modules\Common\V1\Providers;

use App\Modules\Common\V1\Exceptions\AppExceptionHandler;
use App\Modules\Common\V1\Services\Cache\CacheService;
use App\Modules\Common\V1\Services\Cache\CacheServiceInterface;
use App\Modules\Common\V1\Services\Http\HttpService;
use App\Modules\Common\V1\Services\Http\HttpServiceInterface;
use App\Modules\Common\V1\Services\Logger\LoggerService;
use App\Modules\Common\V1\Services\Logger\LoggerServiceInterface;
use App\Modules\Common\V1\Services\Transaction\TransactionService;
use App\Modules\Common\V1\Services\Transaction\TransactionServiceInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoggerServiceInterface::class, LoggerService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->singleton(ExceptionHandler::class, AppExceptionHandler::class);
        $this->app->singleton(HttpServiceInterface::class, HttpService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
