<?php

namespace Database\Seeders;

use App\Models\OrderBadge;
use App\Support\Model\CodeGeneratorHelper;
use Illuminate\Database\Seeder;
use Snortlin\NanoId\NanoId;
use Snortlin\NanoId\NanoIdInterface;
use function App\Support\Helper\get_nanoid;

class OrderBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'در صف بررسی',
            'color_hex' => '#4F86FF',
            'is_starting_badge' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'تایید نشده',
            'color_hex' => '#222428',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'لغو شده',
            'color_hex' => '#E44444',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'مرجوع شده',
            'color_hex' => '#FF9635',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'تحویل به مشتری',
            'color_hex' => '#35FF9D',
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'آماده‌سازی سفارش',
            'color_hex' => '#8C35FF',
        ]);
        OrderBadge::create([
            'code' => get_nanoid(),
            'title' => 'خروج از انبار',
            'color_hex' => '#B3E02C',
        ]);
    }
}
