<?php

namespace App\Enums\Times;

use Carbon\Carbon;

enum TimesEnum: int
{
    case DAYS_30_D = 30; // 30 days;
    case DAYS_7 = 604800; // 7 days in seconds
    case DAYS_3 = 259200; // 3 days in seconds
    case HOURS_2 = 7200; // 2 hours in seconds
    case MINUTES_30 = 1800; // 30 minutes in seconds
    case MINUTES_20 = 1200; // 20 minutes in seconds
    case MINUTES_10 = 600; // 10 minutes in seconds
    case MINUTES_3 = 180; // 3 minutes in seconds
}
