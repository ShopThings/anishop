<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'name' => 'سفید',
                'hex' => '#FFFFFF',
                'is_deletable' => false,
            ],
            [
                'name' => 'مشکی',
                'hex' => '#000000',
                'is_deletable' => false,
            ],
            [
                'name' => 'خاکستری',
                'hex' => '#B8BFC6',
            ],
            [
                'name' => 'قرمز',
                'hex' => '#FF3C3C',
            ],
            [
                'name' => 'صورتی',
                'hex' => '#FF6B9F',
            ],
            [
                'name' => 'بنفش',
                'hex' => '#9775FA',
            ],
            [
                'name' => 'آبی',
                'hex' => '#3786FF',
            ],
            [
                'name' => 'سبز',
                'hex' => '#39E656',
            ],
            [
                'name' => 'زرد',
                'hex' => '#F9D03C',
            ],
            [
                'name' => 'نارنجی',
                'hex' => '#FF9C33',
            ],
        ];

        foreach ($colors as $color) {
            Color::create($color + ['created_at' => now()]);
        }
    }
}
