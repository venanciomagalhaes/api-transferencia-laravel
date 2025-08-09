<?php

namespace App\Modules\Permissions\V1\Enums;

enum PermissionsNameEnum: string
{
    case SEND_TRANSACTION = 'send_transaction';
    case RECEIVE_TRANSACTION = 'receive_transaction';
}
