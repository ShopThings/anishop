<?php

namespace Database\Seeders;

use App\Enums\Sliders\SliderItemOptionsEnum;
use App\Enums\Sliders\SliderPlacesEnum;
use App\Models\FileManager;
use App\Models\Slider;
use App\Models\SliderItem;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Slider $mainSlider */
        $mainSlider = Slider::create([
            'title' => SliderPlacesEnum::getTranslations(SliderPlacesEnum::MAIN),
            'place_in' => SliderPlacesEnum::MAIN->value,
            'priority' => 0,
            'is_published' => true,
            'is_deletable' => false,
            'created_at' => now(),
        ]);

        $bannerImages = FileManager::query()->whereLike('full_path', 'banners/', '{value}%')->limit(5)->get();
        $banners = [];
        foreach ($bannerImages as $bannerImage) {
            $banners[] = new SliderItem([
                'slider_id' => $mainSlider->getKey(),
                'options' => [
                    SliderItemOptionsEnum::IMAGE->value => $bannerImage->getKey(),
                    SliderItemOptionsEnum::LINK->value => 'http://localhost:3000',
                ],
            ]);
        }

        $mainSlider->items()->saveManyQuietly($banners);

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
