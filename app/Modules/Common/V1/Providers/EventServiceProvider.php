<?php

namespace App\Modules\Common\V1\Providers;

use App\Modules\Transaction\V1\Events\TransferSuccessfullyEvent;
use App\Modules\Transaction\V1\Listeners\SendTransferSuccessfullyNotification;
use App\Modules\User\V1\Events\UserCreated;
use App\Modules\Wallet\V1\Listeners\CreateTransactionHistory;
use App\Modules\Wallet\V1\Listeners\CreateWalletForUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Provedor de eventos da aplicação.
 *
 * Responsável por registrar os listeners para os eventos do sistema.
 *
 * Define as relações entre eventos e seus respectivos listeners,
 * permitindo que ações sejam disparadas automaticamente quando os eventos ocorrerem.
 */
class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Inicializa os eventos da aplicação.
     *
     * Registra os listeners para eventos específicos,
     * para que ações relacionadas sejam executadas quando os eventos forem disparados.
     */
    public function boot(): void
    {
        Event::listen(UserCreated::class, CreateWalletForUser::class);
        Event::listen(TransferSuccessfullyEvent::class, SendTransferSuccessfullyNotification::class);
        Event::listen(TransferSuccessfullyEvent::class, CreateTransactionHistory::class);
    }
}
