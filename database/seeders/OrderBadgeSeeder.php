<?php

namespace Database\Seeders;

use App\Models\OrderBadge;
use App\Support\Model\CodeGeneratorHelper;
use Illuminate\Database\Seeder;

class OrderBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'در صف بررسی',
            'color_hex' => '#4F86FF',
            'is_starting_badge' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'تایید نشده',
            'color_hex' => '#222428',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'لغو شده',
            'color_hex' => '#E44444',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'مرجوع شده',
            'color_hex' => '#FF9635',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'تحویل به مشتری',
            'color_hex' => '#35FF9D',
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'آماده‌سازی سفارش',
            'color_hex' => '#8C35FF',
        ]);
        OrderBadge::create([
            'code' => CodeGeneratorHelper::orderBadgeCode(),
            'title' => 'خروج از انبار',
            'color_hex' => '#B3E02C',
        ]);
    }
}
