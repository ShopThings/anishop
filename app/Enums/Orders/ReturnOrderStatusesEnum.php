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
    public static function translationArray(): array
    {
        return [
            self::CHECKING->value => 'در حال بررسی',
            self::DENIED_BY_USER->value => 'لغو توسط کاربر',
            self::ACCEPT->value => 'قبول درخواست',
            self::DENIED->value => 'رد درخواست',
            self::SENDING->value => 'ارسال مرسولات توسط کاربر',
            self::RECEIVED->value => 'دریافت مرسولات توسط پذیرنده',
            self::RETURN_TO_USER->value => 'بازگشت مرسولات توسط پذیرنده',
            self::RECEIVED_BY_USER->value => 'دریافت مرسولات ارسال شده توسط کاربر',
            self::MONEY_RETURNED->value => 'بازگشت وجه پرداخت شده به کاربر',
        ];
    }

    public static function getStatusColor(): array
    {
        return [
            self::CHECKING->value => '#836FFF',
            self::DENIED_BY_USER->value => '#F72798',
            self::ACCEPT->value => '#0EA293',
            self::DENIED->value => '#D24545',
            self::SENDING->value => '#280274',
            self::RECEIVED->value => '#910A67',
            self::RETURN_TO_USER->value => '#FE7A36',
            self::RECEIVED_BY_USER->value => '#7FC7D9',
            self::MONEY_RETURNED->value => '#C922BE',
        ];
    }

    /**
     * @return array
     */
    public static function getEndingStatuses(): array
    {
        return [
            self::DENIED_BY_USER->value,
            self::DENIED->value,
            self::RECEIVED_BY_USER->value,
            self::MONEY_RETURNED->value,
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
    public static function getAdminStatuses(): array
    {
        $statuses = array_map(function ($status) {
            return $status->value;
        }, self::cases());

        return array_filter($statuses, function ($status) {
            return !in_array($status, self::getUserStatuses());
        });
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
