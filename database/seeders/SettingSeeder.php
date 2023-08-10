<?php

namespace Database\Seeders;

use App\Enums\SettingsEnum;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // main group
        Setting::create([
            'name' => SettingsEnum::LOGO->value,
            'setting_value' => '/public/logo.png',
            'group_name' => 'main',
        ]);
        Setting::create([
            'name' => SettingsEnum::LOGO_LIGHT->value,
            'setting_value' => '/public/logo.png',
            'group_name' => 'main',
        ]);
        Setting::create([
            'name' => SettingsEnum::FAVICON->value,
            'setting_value' => '/public/favicon.png',
            'group_name' => 'main',
        ]);
        Setting::create([
            'name' => SettingsEnum::TITLE->value,
            'setting_value' => 'فروشگاه',
            'group_name' => 'main',
        ]);
        Setting::create([
            'name' => SettingsEnum::DESCRIPTION->value,
            'setting_value' => 'وب سایت فروشگاهی',
            'group_name' => 'main',
        ]);
        Setting::create([
            'name' => SettingsEnum::KEYWORDS->value,
            'setting_value' => 'فروشگاه,سایت فروشگاهی,وب سایت فروشگاهی',
            'group_name' => 'main',
        ]);

        // sms group
        Setting::create([
            'name' => SettingsEnum::SMS_ACTIVATION->value,
            'setting_value' => "@shop@" . "\n" . "کد فعالسازی: @code@",
            'group_name' => 'sms',
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_RECOVER_PASS->value,
            'setting_value' => "@shop@" . "\n" . "کد فراموشی کلمه عبور: @code@",
            'group_name' => 'sms',
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_BUY->value,
            'setting_value' => "@shop@" . "\n"
                . "سفارش شما با کد @orderCode@ با موفقیت ثبت شد."
                . "\n"
                . "برای پیگیری سفارش می توانید به پروفایل کاربری، قسمت سفارش ها مراجعه فرمایید.",
            'group_name' => 'sms',
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_ORDER_STATUS->value,
            'setting_value' => "@shop@" . "\n"
                . "مشترک گرامی سفارش به شماره @orderCode@ در وضعیت @status@ قرار گرفت.",
            'group_name' => 'sms',
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_RETURN_ORDER->value,
            'setting_value' => "@shop@" . "\n"
                . "سفارش بازگشتی به کد @code@ به @status@ تغییر یافت."
                . "\n"
                . "برای اطلاعات بیشتر به سایت مراجعه نمایید.",
            'group_name' => 'sms',
        ]);

        // info group
        Setting::create([
            'name' => SettingsEnum::ADDRESS->value,
            'setting_value' => '',
            'group_name' => 'info',
        ]);
        Setting::create([
            'name' => SettingsEnum::PHONES->value,
            'setting_value' => '',
            'group_name' => 'info',
        ]);
        Setting::create([
            'name' => SettingsEnum::STORE_PROVINCE->value,
            'setting_value' => '',
            'group_name' => 'info',
        ]);
        Setting::create([
            'name' => SettingsEnum::STORE_CITY->value,
            'setting_value' => '',
            'group_name' => 'info',
        ]);
        Setting::create([
            'name' => SettingsEnum::LAT_LNG->value,
            'setting_value' => '',
            'group_name' => 'info',
        ]);

        // shop group
        Setting::create([
            'name' => SettingsEnum::CURRENT_CITY_POST_PRICE->value,
            'setting_value' => '0',
            'group_name' => 'shop',
        ]);
        Setting::create([
            'name' => SettingsEnum::MIN_FREE_PRICE->value,
            'setting_value' => '',
            'group_name' => 'shop',
        ]);
        Setting::create([
            'name' => SettingsEnum::DEFAULT_POST_PRICE->value,
            'setting_value' => '',
            'group_name' => 'shop',
            'min_value' => 1500000,
        ]);

        // general group
        Setting::create([
            'name' => SettingsEnum::DEFAULT_IMAGE_PLACEHOLDER->value,
            'setting_value' => '',
            'group_name' => 'general',
        ]);
        Setting::create([
            'name' => SettingsEnum::PRODUCT_EACH_PAGE->value,
            'setting_value' => '',
            'group_name' => 'general',
            'default_value' => '15',
        ]);
        Setting::create([
            'name' => SettingsEnum::BLOG_EACH_PAGE->value,
            'setting_value' => '',
            'group_name' => 'general',
            'default_value' => '15',
        ]);
        Setting::create([
            'name' => SettingsEnum::SOCIALS->value,
            // json object => [[type => 'instagram', link => '#'], ...]
            'setting_value' => '',
            'group_name' => 'general',
        ]);

        // footer group
        Setting::create([
            'name' => SettingsEnum::FOOTER_DESCRIPTION->value,
            'setting_value' => 'تیم هیوا، با ارائه با کیفیت‌ترین محصولات به شما',
            'group_name' => 'footer',
        ]);
        Setting::create([
            'name' => SettingsEnum::FOOTER_COPYRIGHT->value,
            'setting_value' => '&copy; کلیه حقوق این فروشگاه متعلق به شرکت هیوا می‌باشد و هرگونه کپی برداری از آن پیگرد قانونی دارد.',
            'group_name' => 'footer',
        ]);
        Setting::create([
            'name' => SettingsEnum::FOOTER_NAMADS->value,
            'setting_value' => '',
            'group_name' => 'footer',
        ]);
    }
}
