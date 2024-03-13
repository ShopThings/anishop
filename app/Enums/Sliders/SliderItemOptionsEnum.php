<?php

namespace App\Enums\Sliders;

enum SliderItemOptionsEnum: string
{
    case IMAGE = 'image';
    case LINK = 'link';
    case PRODUCT_ID = 'product_id';
    case BLOG_ID = 'blog_id';
}
