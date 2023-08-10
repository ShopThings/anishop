<?php

namespace Database\Seeders;

use App\Models\BlogCommentBadge;
use App\Support\Model\CodeGeneratorHelper;
use Illuminate\Database\Seeder;

class BlogCommentBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogCommentBadge::create([
            'code' => CodeGeneratorHelper::blogCommentBadgeCode(),
            'title' => 'عادی',
            'color_hex' => '#AFC6FF',
            'is_starting_badge' => true,
            'is_deletable' => false,
        ]);
        BlogCommentBadge::create([
            'code' => CodeGeneratorHelper::blogCommentBadgeCode(),
            'title' => 'توضیحات تکمیلی',
            'color_hex' => '#6DFFC9',
        ]);
        BlogCommentBadge::create([
            'code' => CodeGeneratorHelper::blogCommentBadgeCode(),
            'title' => 'انتقادی',
            'color_hex' => '#FF6DA2',
        ]);
    }
}
