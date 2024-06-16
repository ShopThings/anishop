<?php

namespace App\Enums\Charts;

use App\Traits\EnumTranslateTrait;

enum ChartPeriodsEnum: string
{
    use EnumTranslateTrait;

    case TODAY = 'today';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case MONTHS_3 = 'months_3';
    case MONTHS_6 = 'months_6';
    case YEARLY = 'yearly';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::TODAY->value => 'امروز',
            self::WEEKLY->value => 'این هفته',
            self::MONTHLY->value => 'این ماه',
            self::MONTHS_3->value => 'سه ماهه اخیر',
            self::MONTHS_6->value => 'شش ماهه اخیر',
            self::YEARLY->value => 'امسال',
        ];
    }
}
