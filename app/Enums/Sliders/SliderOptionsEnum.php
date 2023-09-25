<?php

namespace App\Enums\Sliders;

enum SliderOptionsEnum: string
{
    case SHOW_ALL_LINK = 'show_all_link';
    case BRAND_ID = 'brand_id';
    case CATEGORY_ID = 'category_id';
    case ORDER_BY = 'order_by';
    case IS_SPECIAL = 'is_special';
    case COUNT = 'count';
}
