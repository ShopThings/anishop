<?php

namespace App\Enums\Products;

enum ProductAttributeTypesEnum: string
{
    case MULTI_SELECT = 'multi_select';
    case SINGLE_SELECT = 'single_select';
}
