<?php

namespace App\Enums\Notification;

enum AccountNotificationTypesEnum: string
{
    case ORDER_PLACED = 'order_placed';
    case RETURN_ORDER_REQUESTED = 'return_order_requested';
    case SETTING_UPDATED = 'setting_updated';
}
