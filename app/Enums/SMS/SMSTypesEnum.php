<?php

namespace App\Enums\SMS;

use App\Traits\EnumTranslateTrait;

enum SMSTypesEnum: string
{
    use EnumTranslateTrait;

    case SIGNUP = 'signup';
    case OTP = 'otp';
    case ACTIVATION = 'activation';
    case RECOVER_PASS = 'recover_pass';
    case BUY = 'buy';
    case ORDER_STATUS = 'order_status';
    case RETURN_ORDER = 'return_order';
    case RETURN_ORDER_STATUS = 'return_order_status';
    case OTHERS = 'others';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::SIGNUP->value => 'ثبت نام',
            self::OTP->value => 'ورود با رمز یکبار مصرف',
            self::ACTIVATION->value => 'فعالسازی حساب',
            self::RECOVER_PASS->value => 'بازگردانی کلمه عبور',
            self::BUY->value => 'خرید',
            self::ORDER_STATUS->value => 'وضعیت سفارش',
            self::RETURN_ORDER->value => 'مرجوع کالا',
            self::RETURN_ORDER_STATUS->value => 'وضعیت مرجوع سفارش',
            self::OTHERS->value => 'متفرقه',
        ];
    }

    /**
     * @param SMSTypesEnum $type
     * @return string[]
     */
    public static function replacementsArray(SMSTypesEnum $type): array
    {
        return match ($type) {
            self::SIGNUP, self::OTHERS => [
                'shop', 'username',
            ],
            self::ACTIVATION, self::OTP => [
                'shop', 'username', 'code',
            ],
            self::RECOVER_PASS => [
                'shop', 'username', 'first_name', 'code',
            ],
            self::BUY => [
                'shop', 'username', 'first_name', 'order_code',
            ],
            self::RETURN_ORDER => [
                'shop', 'username', 'first_name', 'return_code', 'order_code',
            ],
            self::ORDER_STATUS => [
                'shop', 'username', 'first_name', 'order_code', 'status',
            ],
            self::RETURN_ORDER_STATUS => [
                'shop', 'username', 'first_name', 'return_code', 'order_code', 'status',
            ],
        };
    }
}
