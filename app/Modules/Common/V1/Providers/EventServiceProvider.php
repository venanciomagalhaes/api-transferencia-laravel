<?php

namespace App\Modules\Common\V1\Providers;

use App\Modules\Wallet\V1\Listeners\CreateWalletForUser;
use App\Modules\User\V1\Events\UserCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;


class EventServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(UserCreated::class, CreateWalletForUser::class);
    }
}
