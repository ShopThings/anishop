<?php

namespace App\Enums\SMS;

use App\Traits\EnumTranslateTrait;

enum SMSSenderTypesEnum: string
{
    use EnumTranslateTrait;

    case SYSTEM = 'system';
    case USER = 'user';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::SYSTEM->value => 'سیستم',
            self::USER->value => 'سایت',
        ];
    }
}
