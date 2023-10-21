<?php

namespace App\Enums\Orders;

use App\Traits\EnumTranslateTrait;

enum ReturnOrderStatusesEnum: string
{
    use EnumTranslateTrait;

    case CHECKING = 'checking';
    case DENIED_BY_USER = 'denied_by_user';
    case ACCEPT = 'accept';
    case DENIED = 'denied';
    case SENDING = 'sending';
    case RECEIVED = 'received';
    case MONEY_RETURNED = 'money_returned';

    /**
     * @return string[]
     */
    private static function translationArray(): array
    {
        return [
            self::CHECKING->value => 'در حال بررسی',
            self::DENIED_BY_USER->value => 'لغو توسط کاربر',
            self::ACCEPT->value => 'قبول درخواست',
            self::DENIED->value => 'رد درخواست',
            self::SENDING->value => 'ارسال مرسولات توسط کاربر',
            self::RECEIVED->value => 'دریافت مرسولات توسط پذیرنده',
            self::MONEY_RETURNED->value => 'بازگشت وجه پرداخت شده به کاربر',
        ];
    }

    /**
     * @return array
     */
    public static function getDeletableStatuses(): array
    {
        return [
            self::CHECKING->value,
            self::DENIED_BY_USER->value,
            self::DENIED->value,
        ];
    }
}
