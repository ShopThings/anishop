<?php

namespace App\Enums\Settings;

enum SettingGroupsEnum: string
{
    case MAIN = 'main';
    case SMS = 'sms';
    case INFO = 'info';
    case SHOP = 'shop';
    case SOCIAL = 'social';
    case GENERAL = 'general';
    case FOOTER = 'footer';
}
