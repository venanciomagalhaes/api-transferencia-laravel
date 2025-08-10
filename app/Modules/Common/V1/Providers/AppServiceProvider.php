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

/**
 * Provedor de serviços da aplicação.
 *
 * Responsável por registrar e configurar os bindings e singletons
 * do container de serviços do Laravel para a aplicação.
 *
 * Faz o binding de interfaces para suas implementações concretas,
 * garantindo a injeção de dependências correta ao longo do sistema.
 * Também registra o manipulador de exceções personalizado da aplicação.
 *
 */
class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(LoggerServiceInterface::class, LoggerService::class);
        $this->app->bind(CacheServiceInterface::class, CacheService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->singleton(ExceptionHandler::class, AppExceptionHandler::class);
        $this->app->singleton(HttpServiceInterface::class, HttpService::class);
    }


    public function boot(): void
    {
        //
    }
}
