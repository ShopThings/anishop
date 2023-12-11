<?php

namespace App\Enums;

use App\Traits\EnumTranslateTrait;

enum ContactStatusesEnum: string
{
    use EnumTranslateTrait;

    case UNREAD = 'unread';
    case READ = 'read';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::UNREAD->value => 'خوانده نشده',
            self::READ->value => 'خوانده شده',
        ];
    }
}
