<?php

namespace App\Enums\Products;

enum ChangeMultipleProductPriceTypesEnum: string
{
    case INCREASE = 'increase';
    case DECREASE = 'decrease';
}
