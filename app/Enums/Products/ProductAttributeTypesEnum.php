<?php

namespace App\Enums\Products;

use App\Traits\EnumTranslateTrait;

enum ProductAttributeTypesEnum: string
{
    use EnumTranslateTrait;

    case MULTI_SELECT = 'multi_select';
    case SINGLE_SELECT = 'single_select';

    /**
     * @return string[]
     */
    public static function translationArray(): array
    {
        return [
            self::MULTI_SELECT->value => 'چند انتخابی',
            self::SINGLE_SELECT->value => 'تک انتخابی',
        ];
    }
}
