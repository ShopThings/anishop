<?php

namespace App\Enums\Orders;

enum ReturnOrderStatusesEnum: string
{
    case CHECKING = 'checking';
    case DENIED_BY_USER = 'denied_by_user';
    case ACCEPT = 'accept';
    case DENIED = 'denied';
    case SENDING = 'sending';
    case RECEIVED = 'received';
    case MONEY_RETURNED = 'money_returned';
}
