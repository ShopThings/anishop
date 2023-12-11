<?php

namespace App\Enums\Sliders;

enum SliderPlacesEnum: string
{
    case MAIN = 'main';
    case MAIN_BLOG = 'main_blog';
    case MAIN_BLOG_SIDE = 'main_blog_side';
    case MAIN_SLIDERS = 'main_sliders';
    case MAIN_SLIDER_IMAGES = 'main_slider_images';
    case AMAZING_OFFER = 'amazing_offer';

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
