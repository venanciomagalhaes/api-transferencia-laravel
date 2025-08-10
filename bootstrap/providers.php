<?php

use App\Modules\Common\V1\Providers\AppServiceProvider;
use App\Modules\Common\V1\Providers\EventServiceProvider;
use App\Modules\Permissions\V1\Providers\PermissionServiceProvider;
use App\Modules\Transaction\V1\Providers\TransactionModuleProvider;
use App\Modules\Wallet\V1\Providers\WalletModuleProvider;
use App\Modules\User\V1\Providers\UserModuleProvider;

return [
    AppServiceProvider::class,
    EventServiceProvider::class,
    UserModuleProvider::class,
    WalletModuleProvider::class,
    PermissionServiceProvider::class,
    TransactionModuleProvider::class,
];
