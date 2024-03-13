<?php

namespace App\Enums\Sliders;

use App\Traits\EnumTranslateTrait;

enum SliderPlacesEnum: string
{
    use EnumTranslateTrait;

    case MAIN = 'main';
    case MAIN_BLOG = 'main_blog';
    case MAIN_BLOG_SIDE = 'main_blog_side';
    case MAIN_SLIDERS = 'main_sliders';
    case MAIN_SLIDER_IMAGES = 'main_slider_images';
    case AMAZING_OFFER = 'amazing_offer';

    /**
     * @return array
     */
    public static function translationArray(): array
    {
        return [
            self::MAIN->value => 'اسلایدر اصلی',
            self::MAIN_BLOG->value => 'اسلایدر اصلی بلاگ',
            self::MAIN_BLOG_SIDE->value => 'اسلایدر اصلی بلاگ - اسلایدهای کناری',
            self::MAIN_SLIDERS->value => 'اسلایدرهای محصول',
            self::MAIN_SLIDER_IMAGES->value => 'تصویر محصولات',
            self::AMAZING_OFFER->value => 'پیشنهادهای شگفت‌انگیز',
        ];
    }

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
