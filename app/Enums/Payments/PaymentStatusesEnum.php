<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;
use Illuminate\Support\Str;

enum PaymentStatusesEnum: int
{
    use EnumTranslateTrait;

    case PARTIAL_SUCCESS = 2;
    case SUCCESS = 1;
    case FAILED = 0;
    case WAIT_VERIFY = -7;
    case WAIT = -8;
    case NOT_PAYED = -9;

    /**
     * @return string[]
     */
    public static function translationArray(): array
    {
        return [
            self::PARTIAL_SUCCESS->value => 'پرداخت بخشی از مبلغ',
            self::SUCCESS->value => 'پرداخت موفق',
            self::FAILED->value => 'پرداخت ناموفق',
            self::WAIT_VERIFY->value => 'در انتظار تایید',
            self::WAIT->value => 'در انتظار پرداخت',
            self::NOT_PAYED->value => 'پرداخت نشده',
        ];
    }
}
