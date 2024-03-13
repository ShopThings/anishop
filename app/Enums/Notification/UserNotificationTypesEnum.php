<?php

namespace App\Enums\Notification;

use App\Traits\EnumTranslateTrait;

enum UserNotificationTypesEnum: string
{
    use EnumTranslateTrait;

    case SIGNUP = 'signup';
    case UPDATE_INFO = 'update_info';
    case COMMENTED = 'commented';
    case RECOVER_PASS = 'recover_pass';
    case BUY = 'buy';
    case ORDER_STATUS = 'order_status';
    case RETURN_ORDER = 'return_order';
    case OTHERS = 'others';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::SIGNUP->value => 'ثبت نام',
            self::UPDATE_INFO->value => 'بروزرسانی اطلاعات',
            self::COMMENTED->value => 'ثبت نظر',
            self::RECOVER_PASS->value => 'بازگردانی کلمه عبور',
            self::BUY->value => 'خرید',
            self::ORDER_STATUS->value => 'وضعیت سفارش',
            self::RETURN_ORDER->value => 'مرجوع کالا',
            self::OTHERS->value => 'متفرقه',
        ];
    }
}
