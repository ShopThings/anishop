<?php

namespace Database\Seeders;

use App\Enums\Sliders\SliderPlacesEnum;
use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::create([
            'title' => SliderPlacesEnum::getTranslations(SliderPlacesEnum::MAIN),
            'place_in' => SliderPlacesEnum::MAIN->value,
            'priority' => 0,
            'is_published' => false,
            'is_deletable' => false,
            'created_at' => now(),
        ]);
        //
        Slider::create([
            'title' => SliderPlacesEnum::getTranslations(SliderPlacesEnum::MAIN_BLOG),
            'place_in' => SliderPlacesEnum::MAIN_BLOG->value,
            'priority' => 0,
            'is_published' => false,
            'is_deletable' => false,
            'created_at' => now(),
        ]);
        Slider::create([
            'title' => SliderPlacesEnum::getTranslations(SliderPlacesEnum::MAIN_BLOG_SIDE),
            'place_in' => SliderPlacesEnum::MAIN_BLOG_SIDE->value,
            'priority' => 0,
            'is_published' => false,
            'is_deletable' => false,
            'created_at' => now(),
        ]);
        //
        Slider::create([
            'title' => SliderPlacesEnum::getTranslations(SliderPlacesEnum::AMAZING_OFFER),
            'place_in' => SliderPlacesEnum::AMAZING_OFFER->value,
            'priority' => 0,
            'is_published' => false,
            'is_deletable' => false,
            'created_at' => now(),
        ]);
    }
}
