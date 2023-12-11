<?php

namespace App\Enums\SMS;

use App\Traits\EnumTranslateTrait;

enum SMSTypesEnum: string
{
    use EnumTranslateTrait;

    case SIGNUP = 'signup';
    case ACTIVATION = 'activation';
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
            self::ACTIVATION->value => 'فعالسازی حساب',
            self::RECOVER_PASS->value => 'بازگردانی کلمه عبور',
            self::BUY->value => 'خرید',
            self::ORDER_STATUS->value => 'وضعیت سفارش',
            self::RETURN_ORDER->value => 'مرجوع کالا',
            self::OTHERS->value => 'متفرقه',
        ];
    }

    public static function replacementsArray(SMSTypesEnum $type): array
    {
        return match ($type) {
            self::SIGNUP => [
                'shop', 'username',
            ],
            self::ACTIVATION => [
                'shop', 'username', 'code',
            ],
            self::RECOVER_PASS => [
                'shop', 'username', 'first_name', 'code',
            ],
            self::BUY, self::RETURN_ORDER => [
                'shop', 'username', 'first_name', 'order_code',
            ],
            self::ORDER_STATUS => [
                'shop', 'username', 'first_name', 'order_code', 'status',
            ],
            self::OTHERS => [
                'shop', 'username',
            ],
        };
    }
}
