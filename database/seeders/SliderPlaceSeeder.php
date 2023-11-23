<?php

namespace Database\Seeders;

use App\Enums\Sliders\SliderPlacesEnum;
use App\Models\SliderPlace;
use Illuminate\Database\Seeder;

class SliderPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SliderPlace::create([
            'title' => 'اسلایدر اصلی',
            'place_in' => SliderPlacesEnum::MAIN,
        ]);
        SliderPlace::create([
            'title' => 'اسلایدر محصولات',
            'place_in' => SliderPlacesEnum::MAIN_SLIDERS,
        ]);
        SliderPlace::create([
            'title' => 'تصویر محصولات',
            'place_in' => SliderPlacesEnum::MAIN_SLIDER_IMAGES,
        ]);
    }
}
