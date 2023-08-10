<?php

namespace App\Enums\SMS;

enum SMSTypesEnum: string
{
    case ACTIVATION = 'activation';
    case RECOVER_PASS = 'recover_pass';
    case BUY = 'buy';
    case ORDER_STATUS = 'order_status';
    case RETURN_ORDER = 'return_order';
}
