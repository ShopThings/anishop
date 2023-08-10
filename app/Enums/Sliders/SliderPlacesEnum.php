<?php

namespace App\Enums\Sliders;

enum SliderPlacesEnum: string
{
    case MAIN = 'main';
    case MAIN_SLIDERS = 'main_sliders';

    /**
     * @return SliderPlacesEnum[]
     */
    public static function getCreatablePlaces(): array
    {
        return [
            self::MAIN_SLIDERS,
        ];
    }
}
