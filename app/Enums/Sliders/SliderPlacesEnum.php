<?php

namespace App\Enums\Sliders;

enum SliderPlacesEnum: string
{
    case MAIN = 'main';
    case MAIN_SLIDERS = 'main_sliders';
    case MAIN_SLIDER_IMAGES = 'main_slider_images';

    /**
     * @return SliderPlacesEnum[]
     */
    public static function getCreatablePlaces(): array
    {
        return [
            self::MAIN_SLIDERS,
            self::MAIN_SLIDER_IMAGES,
        ];
    }
}
