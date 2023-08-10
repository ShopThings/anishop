<?php

namespace App\Enums\Menus;

enum MenuPlacesEnum: string
{
    case MAIN = 'main';
    case TOP_MENU = 'top_menu';
    case FOOTER = 'footer';
    case BLOG = 'blog';
}
