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
            FileManagerSeeder::class,
            //
            ProvinceAndCitySeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            //
            SettingSeeder::class,
            //
            SendMethodSeeder::class,
            //
            BlogCommentBadgeSeeder::class,
            BlogSeeder::class,
            BlogCommentSeeder::class,
            //
            MenuAndPlaceSeeder::class,
            SliderSeeder::class,
            //
            ColorSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            ProductCommentSeeder::class,
            //
            OrderBadgeSeeder::class,
        ]);
    }
}
