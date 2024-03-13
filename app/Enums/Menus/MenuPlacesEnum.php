<?php

namespace App\Enums\Menus;

use App\Traits\EnumTranslateTrait;

enum MenuPlacesEnum: string
{
    use EnumTranslateTrait;

    case TOP_MENU = 'top_menu';
    case TOP_MENU_BLOG = 'top_menu_blog';
    case FOOTER = 'footer';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::TOP_MENU->value => 'منوی بالای صفحه',
            self::TOP_MENU_BLOG->value => 'منوی بالای صفحه بلاگ',
            self::FOOTER->value => 'منوی فوتر/پانوشت',
        ];
    }
}
