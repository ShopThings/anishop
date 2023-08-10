<?php

namespace App\Enums\SMS;

enum SMSSenderTypesEnum: string
{
    case SYSTEM = 'system';
    case USER = 'user';
}
