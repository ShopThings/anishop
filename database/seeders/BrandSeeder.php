<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\FileManager;
use App\Support\Converters\CharacterConverter;
use App\Support\Converters\NumberConverter;
use Illuminate\Database\Eloquent\Collection;
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

        /**
         * @var Collection $brandImages
         */
        $brandImages = FileManager::query()->whereLike('full_path', 'brands/', '{value}%')->get();
        $fileBrandMap = [
            'acer' => 'b1',
            'asus' => 'b2',
            'lenovo' => 'b3',
            'hp' => 'b4',
            'samsung' => 'b5',
            'lg' => 'b6',
            'snowa' => 'b7',
            'newtech' => 'b8',
            'huawei' => 'b9',
            'sony' => 'b10',
            'daewoo' => 'b11',
        ];

        $sampleBrands = [
            [
                'name' => 'ایسر',
                'latin_name' => 'Acer',
                'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian('ایسر')),
                'slug' => str_slug_persian('ایسر'),
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['acer'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['lenovo'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['asus'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['hp'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['samsung'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['snowa'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['huawei'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['newtech'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['sony'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['lg'])?->id,
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
                'image_id' => $brandImages->firstWhere('name', $fileBrandMap['daewoo'])?->id,
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
