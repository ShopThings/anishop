<?php

namespace App\Enums\Times;

enum TimeFormatsEnum: string
{
    case NORMAL_DATETIME = 'Y-m-d H:i';
    case DEFAULT = 'j F Y';
    case NOTIFICATION_DEFAULT = 'j F Y (H:i)';
    case DEFAULT_WITH_TIME = 'j F Y در ساعت H و i دقیقه';
    case SITEMAP = 'Y-m-d\TH:i:s.uP';
    case ARCHIVE = 'F Y';
    case EXPORT_FILENAME_WITH_TIME_AND_SECONDS = 'Y-m-d (در ساعت H و i دقیقه و s ثانیه)';
}
