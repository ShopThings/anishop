<?php

namespace App\Enums\Notification;

use App\Traits\EnumTranslateTrait;

enum UserNotificationPrioritiesEnum: int
{
    use EnumTranslateTrait;

    case HIGH = 10;
    case NORMAL = 0;
    case LOW = -10;

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::HIGH->value => 'بالا',
            self::NORMAL->value => 'عادی',
            self::LOW->value => 'پایین',
        ];
    }
}
