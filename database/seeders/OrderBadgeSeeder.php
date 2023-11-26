<?php

namespace Database\Seeders;

use App\Models\OrderBadge;
use App\Support\Model\CodeGeneratorHelper;
use Illuminate\Database\Seeder;
use Snortlin\NanoId\NanoId;
use Snortlin\NanoId\NanoIdInterface;

class OrderBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'در صف بررسی',
            'color_hex' => '#4F86FF',
            'is_starting_badge' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'تایید نشده',
            'color_hex' => '#222428',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'لغو شده',
            'color_hex' => '#E44444',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'مرجوع شده',
            'color_hex' => '#FF9635',
            'should_return_order_product' => true,
            'is_title_editable' => false,
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'تحویل به مشتری',
            'color_hex' => '#35FF9D',
            'is_deletable' => false,
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'آماده‌سازی سفارش',
            'color_hex' => '#8C35FF',
        ]);
        OrderBadge::create([
            'code' => NanoId::nanoId(
                NanoIdInterface::SIZE_DEFAULT,
                NanoIdInterface::ALPHABET_ALPHA_NUMERIC_READABLE
            ),
            'title' => 'خروج از انبار',
            'color_hex' => '#B3E02C',
        ]);
    }
}
