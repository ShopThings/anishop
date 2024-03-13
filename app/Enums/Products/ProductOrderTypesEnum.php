<?php

namespace App\Enums\Products;

enum ProductOrderTypesEnum: string
{
    case NEWEST = 'newest';
    case OLDEST = 'oldest';
    case MOST_DISCOUNT = 'most_discount';
    case MOST_VIEWED = 'most_viewed';
    case LEAST_EXPENSIVE = 'least_expensive';
    case MOST_EXPENSIVE = 'most_expensive';
}
