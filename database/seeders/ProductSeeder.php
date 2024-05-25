<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FileManager;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all()->pluck('id')->toArray();
        $category = Category::all()->pluck('id')->toArray();
        $images = FileManager::query()->whereLike('path', 'products')->get()->pluck('id')->toArray();

//        $product = Product::create([
//            'brand_id' => $brands[mt_rand(0, count($brands) - 1)],
//            'category_id' => $category[mt_rand(0, count($category) - 1)],
//            'title' => '',
//            'slug' => str_slug_persian(),
//            'image_id' => '',
//            'description' => '',
//            'properties' => '',
//            'quick_properties' => '',
//            'unit_name' => '',
//            'keywords' => '',
//            'is_available' => '',
//            'is_commenting_allowed' => '',
//            'is_published' => '',
//            'created_at' => now(),
//            'created_by' => 1,
//        ]);
    }
}
