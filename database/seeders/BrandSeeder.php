<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Support\Converters\CharacterConverter;
use App\Support\Converters\NumberConverter;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'ندارد',
            'latin_name' => 'unknown',
            'escaped_name' => 'ندارد',
            'slug' => str_slug_persian('unknown'),
            'show_in_slider' => false,
            'is_deletable' => false,
        ]);

        $sampleBrands = [
            [
                'name' => 'ایسر',
                'latin_name' => 'Acer',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('ایسر')),
                'slug' => str_slug_persian('ایسر'),
                'keywords' => ['acer', 'ایسر'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'لنوو',
                'latin_name' => 'Lenovo',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('لنوو')),
                'slug' => str_slug_persian('لنوو'),
                'keywords' => ['lenovo', 'لنوو'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'ایسوس',
                'latin_name' => 'ASUS',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('ایسوس')),
                'slug' => str_slug_persian('ایسوس'),
                'keywords' => ['asus', 'ایسوس'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'اچ پی',
                'latin_name' => 'hp',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('اچ پی')),
                'slug' => str_slug_persian('اچ پی'),
                'keywords' => ['hp', 'اچ پی'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'سامسونگ',
                'latin_name' => 'Samsung',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('سامسونگ')),
                'slug' => str_slug_persian('سامسونگ'),
                'keywords' => ['samsung', 'سامسونگ'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'اسنوا',
                'latin_name' => 'snowa',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('اسنوا')),
                'slug' => str_slug_persian('اسنوا'),
                'keywords' => ['snowa', 'اسنوا'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'هواوی',
                'latin_name' => 'Huawei',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('هواوی')),
                'slug' => str_slug_persian('هواوی'),
                'keywords' => ['huawei', 'هواوی'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'نیوتک',
                'latin_name' => 'NewTech',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('نیوتک')),
                'slug' => str_slug_persian('نیوتک'),
                'keywords' => ['newtech', 'نیوتک'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'سونی',
                'latin_name' => 'Sony',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('سونی')),
                'slug' => str_slug_persian('سونی'),
                'keywords' => ['sony', 'سونی'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'ال جی',
                'latin_name' => 'LG',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('ال جی')),
                'slug' => str_slug_persian('ال جی'),
                'keywords' => ['lg', 'ال جی'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            [
                'name' => 'دوو',
                'latin_name' => 'DAEWOO',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('دوو')),
                'slug' => str_slug_persian('دوو'),
                'keywords' => ['daewoo', 'دوو'],
                'show_in_slider' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
        ];

        foreach ($sampleBrands as $brand) {
            Brand::create($brand);
        }
    }
}
