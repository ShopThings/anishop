<?php

namespace Database\Seeders;

use App\Models\BlogCommentBadge;
use Illuminate\Database\Seeder;

class BlogCommentBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCommentBadge::create([
            'title' => 'عادی',
            'color_hex' => '#AFC6FF',
            'is_starting_badge' => true,
            'is_deletable' => false,
        ]);

        if (!app()->isProduction()) {
            BlogCommentBadge::create([
                'title' => 'توضیحات تکمیلی',
                'color_hex' => '#6DFFC9',
            ]);
            BlogCommentBadge::create([
                'title' => 'انتقادی',
                'color_hex' => '#FF6DA2',
            ]);
        }
    }
}
