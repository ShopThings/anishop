<?php

namespace App\Enums\Times;

enum TimeFormatsEnum: string
{
    case DEFAULT = 'j F Y';
    case DEFAULT_WITH_TIME = 'j F Y در ساعت H و i دقیقه';
    case SITEMAP = 'Y-m-d\TH:i:s.uP';
    case ARCHIVE = 'F Y';
}
