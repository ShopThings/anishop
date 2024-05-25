<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;

enum PaymentStatusesEnum: int
{
    use EnumTranslateTrait;

    case RETURNED_SUCCESS = 4;
    case UNWANTED_SUCCESS = 3;
    case PARTIAL_SUCCESS = 2;
    case SUCCESS = 1;
    case FAILED = 0;
    case WAIT_VERIFY = -7;
    case WAIT = -8;
    case NOT_PAID = -9;

    /**
     * @return string[]
     */
    public static function translationArray(): array
    {
        return [
            self::RETURNED_SUCCESS->value => 'پرداخت شده(برگشت داده شده)',
            self::UNWANTED_SUCCESS->value => 'پرداخت شده(منتظر برگشت)',
            self::PARTIAL_SUCCESS->value => 'پرداخت بخشی از مبلغ',
            self::SUCCESS->value => 'پرداخت موفق',
            self::FAILED->value => 'پرداخت ناموفق',
            self::WAIT_VERIFY->value => 'در انتظار تایید',
            self::WAIT->value => 'در انتظار پرداخت',
            self::NOT_PAID->value => 'پرداخت نشده',
        ];
    }

    public static function getStatusColor(): array
    {
        return [
            self::RETURNED_SUCCESS->value => '#C8EF51',
            self::UNWANTED_SUCCESS->value => '#70EBFF',
            self::PARTIAL_SUCCESS->value => '#836FFF',
            self::SUCCESS->value => '#0EA293',
            self::FAILED->value => '#F72798',
            self::WAIT_VERIFY->value => '#836FFF',
            self::WAIT->value => '#280274',
            self::NOT_PAID->value => '#D24545',
        ];
    }
}
