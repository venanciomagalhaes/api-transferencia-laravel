<?php

namespace App\Modules\Common\V1\Providers;

use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Transaction\V1\Listeners\SendTransferSuccessfullyNotification;
use App\Modules\User\V1\Events\UserCreated;
use App\Modules\Wallet\V1\Listeners\CreateWalletForUser;
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
        Event::listen(TransferSuccessfullyEvent::class, SendTransferSuccessfullyNotification::class);
    }
}
