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
    case RETURN_TO_USER = 'return_to_user';
    case RECEIVED_BY_USER = 'received_by_user';
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
            self::RETURN_TO_USER->value => 'بازگشت مرسولات توسط پذیرنده',
            self::RECEIVED_BY_USER->value => 'دریافت مرسولات ارسال شده توسط پذیرنده',
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

    /**
     * @return array
     */
    public static function getUserStatuses(): array
    {
        return [
            self::DENIED_BY_USER->value,
            self::SENDING->value,
            self::RECEIVED_BY_USER->value,
        ];
    }
}
