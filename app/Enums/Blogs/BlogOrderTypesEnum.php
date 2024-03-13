<?php

namespace App\Enums\Blogs;

enum BlogOrderTypesEnum: string
{
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case MOST_VIEWED = 'most_viewed';
}
