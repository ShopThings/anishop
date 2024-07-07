<?php

namespace Database\Seeders;

use App\Enums\DatabaseEnum;
use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // privacy-policy
        StaticPage::create([
            'title' => 'قوانین و مقررات',
            'description' => 'لطفا وارد نمایید...',
            'url' => 'privacy-policy',
            'keywords' => ['قوانین و مقررات سایت', 'قوانین', 'مقررات'],
            'is_published' => DatabaseEnum::DB_YES,
            'is_deletable' => DatabaseEnum::DB_NO,
            'created_at' => now(),
        ]);

        // how-payment-works
        StaticPage::create([
            'title' => 'نحوه پرداخت سفارش',
            'description' => 'لطفا وارد نمایید...',
            'url' => 'how-payment-works',
            'keywords' => ['نحوه پرداخت', 'طریقه پرداخت', 'پرداخت چند مرحله ای'],
            'is_published' => DatabaseEnum::DB_YES,
            'is_deletable' => DatabaseEnum::DB_NO,
            'created_at' => now(),
        ]);

        // how-to-cancel-order
        StaticPage::create([
            'title' => 'نحوه لغو سفارش',
            'description' => 'لطفا وارد نمایید...',
            'url' => 'how-to-cancel-order',
            'keywords' => ['نحوه لغو سفارش', 'طریقه لغو سفارش', 'لغو سفارش'],
            'is_published' => DatabaseEnum::DB_YES,
            'is_deletable' => DatabaseEnum::DB_NO,
            'created_at' => now(),
        ]);

        // how-to-return-order
        StaticPage::create([
            'title' => 'نحوه مرجوع نمودن سفارش',
            'description' => 'لطفا وارد نمایید...',
            'url' => 'how-to-return-order',
            'keywords' => [
                'نحوه مرجوع سفارش',
                'طریقه مرجوع سفارش',
                'مرجوع سفارش',
                'نحوه مرجوع نمودن سفارش',
                'طریقه مرجوع نمودن سفارش',
                'مرجوع نمودن سفارش'
            ],
            'is_published' => DatabaseEnum::DB_YES,
            'is_deletable' => DatabaseEnum::DB_NO,
            'created_at' => now(),
        ]);
    }
}
