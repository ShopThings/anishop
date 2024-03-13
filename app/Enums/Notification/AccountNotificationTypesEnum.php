<?php

namespace App\Enums\Notification;

enum AccountNotificationTypesEnum: string
{
    case ORDER_PLACED = 'order_placed';
    case ORDER_STATUS_CHANGED = 'order_status_changed';
    case RETURN_ORDER_REQUESTED = 'return_order_requested';
    case RETURN_ORDER_STATUS_CHANGED = 'return_order_status_changed';
    case SETTING_UPDATED = 'setting_updated';
    case CONTACT_ADDED = 'contact_added';
    case COMPLAINT_ADDED = 'complaint_added';
}
