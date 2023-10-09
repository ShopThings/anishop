<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;
use Illuminate\Support\Str;

enum PaymentStatusesEnum: int
{
    use EnumTranslateTrait;

    case SUCCESS = 1;
    case FAILED = 0;
    case WAIT_VERIFY = -7;
    case WAIT = -8;
    case NOT_PAYED = -9;

    /**
     * @return string[]
     */
    protected static function translationArray(): array
    {
        return [
            self::SUCCESS->value => 'پرداخت موفق',
            self::FAILED->value => 'پرداخت ناموفق',
            self::WAIT_VERIFY->value => 'در انتظار تایید',
            self::WAIT->value => 'در انتظار پرداخت',
            self::NOT_PAYED->value => 'پرداخت نشده',
        ];
    }
}
