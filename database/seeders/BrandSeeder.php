<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            'slug' => Str::slug('unknown'),
            'show_in_slider' => false,
            'is_deletable' => false,
        ]);
    }
}
