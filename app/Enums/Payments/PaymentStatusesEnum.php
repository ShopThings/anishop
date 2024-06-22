<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;

enum PaymentStatusesEnum: string
{
    use EnumTranslateTrait;

    case SUCCESS = 'success';
    case FAILED = 'failed';
    case UNWANTED_SUCCESS = 'unwanted_success';
    case RETURNED_SUCCESS = 'returned_success';
    case PARTIAL_SUCCESS = 'partial_success';
    case WAIT_VERIFY = 'wait_verify';
    case PENDING = 'pending';
    case NOT_PAID = 'not_paid';

    /**
     * @return string[]
     */
    public static function translationArray(): array
    {
        return [
            self::SUCCESS->value => 'پرداخت موفق',
            self::FAILED->value => 'پرداخت ناموفق',
            self::UNWANTED_SUCCESS->value => 'پرداخت شده(منتظر برگشت)',
            self::RETURNED_SUCCESS->value => 'پرداخت شده(برگشت داده شده)',
            self::PARTIAL_SUCCESS->value => 'پرداخت بخشی از مبلغ',
            self::WAIT_VERIFY->value => 'در انتظار تایید',
            self::PENDING->value => 'در انتظار پرداخت',
            self::NOT_PAID->value => 'پرداخت نشده',
        ];
    }

    public static function getStatusColor(): array
    {
        return [
            self::SUCCESS->value => '#0EA293',
            self::FAILED->value => '#F72798',
            self::UNWANTED_SUCCESS->value => '#70EBFF',
            self::RETURNED_SUCCESS->value => '#C8EF51',
            self::PARTIAL_SUCCESS->value => '#836FFF',
            self::WAIT_VERIFY->value => '#836FFF',
            self::PENDING->value => '#280274',
            self::NOT_PAID->value => '#D24545',
        ];
    }
}
