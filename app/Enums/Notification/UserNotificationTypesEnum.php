<?php

namespace App\Enums\Notification;

use App\Traits\EnumTranslateTrait;

enum UserNotificationTypesEnum: string
{
    use EnumTranslateTrait;

    // typical users notification types
    case SIGNUP = 'signup';
    case UPDATE_INFO = 'update_info';
    case COMMENTED = 'commented';
    case RECOVER_PASS = 'recover_pass';
    case BUY = 'buy';
    case ORDER_STATUS = 'order_status';
    case RETURN_ORDER = 'return_order';
    case RETURN_ORDER_STATUS = 'return_order_status';
    case PAYMENT = 'payment';
    case OTHERS = 'others';

    // admin users notification types
    case EXPORT = 'export';
    case ORDER_PLACED = 'order_placed';
    case RETURN_ORDER_REQUESTED = 'return_order_requested';
    case SETTING_CHANGED = 'setting_changed';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::SIGNUP->value => 'ثبت نام',
            self::UPDATE_INFO->value => 'بروزرسانی اطلاعات',
            self::COMMENTED->value => 'ثبت دیدگاه',
            self::RECOVER_PASS->value => 'بازگردانی کلمه عبور',
            self::BUY->value => 'خرید',
            self::ORDER_STATUS->value => 'وضعیت سفارش',
            self::RETURN_ORDER->value => 'مرجوع کالا',
            self::RETURN_ORDER_STATUS->value => 'وضعیت مرجوع سفارش',
            self::PAYMENT->value => 'پرداخت',
            self::OTHERS->value => 'متفرقه',
            //
            self::EXPORT->value => 'خروجی گرفتن',
            self::ORDER_PLACED->value => 'ثبت سفارش',
            self::RETURN_ORDER_REQUESTED->value => 'ثبت سفارش مرجوعی',
            self::SETTING_CHANGED->value => 'تغییر تنظیمات',
        ];
    }

    /**
     * @return UserNotificationTypesEnum[]
     */
    public static function getUserTypes(): array
    {
        return [
            self::SIGNUP,
            self::UPDATE_INFO,
            self::COMMENTED,
            self::RECOVER_PASS,
            self::BUY,
            self::ORDER_STATUS,
            self::RETURN_ORDER,
            self::RETURN_ORDER_STATUS,
            self::PAYMENT,
            self::OTHERS,
        ];
    }

    /**
     * @return UserNotificationTypesEnum[]
     */
    public static function getAdminTypes(): array
    {
        return [
            self::EXPORT,
            self::ORDER_PLACED,
            self::RETURN_ORDER_REQUESTED,
            self::SETTING_CHANGED,
        ];
    }
}
