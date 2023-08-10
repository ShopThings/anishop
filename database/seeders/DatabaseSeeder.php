<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceAndCitySeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            BlogCommentBadgeSeeder::class,
            SliderPlaceSeeder::class,
            MenuAndPlaceSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            OrderBadgeSeeder::class,
        ]);
    }
}
