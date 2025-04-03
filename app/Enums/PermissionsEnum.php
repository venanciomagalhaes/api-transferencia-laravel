<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case MAKE_A_TRANSFER = 'make-a-transfer';
    case RECEIVE_A_TRANSFER = 'receive-a-transfer';
}
