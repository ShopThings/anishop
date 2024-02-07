<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function App\Support\Helper\str_slug_persian;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'بدون دسته‌بندی',
            'escaped_name' => 'بدون دسته‌بندی',
            'slug' => str_slug_persian('بدون دسته‌بندی'),
            'show_in_menu' => false,
            'show_in_search_side_menu' => false,
            'show_in_slider' => false,
            'is_deletable' => false,
        ]);
    }
}
