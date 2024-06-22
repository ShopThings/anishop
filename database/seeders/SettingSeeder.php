<?php

namespace Database\Seeders;

use App\Enums\Settings\SettingGroupsEnum;
use App\Enums\Settings\SettingsEnum;
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
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::LOGO_LIGHT->value,
            'setting_value' => '/public/logo.png',
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::FAVICON->value,
            'setting_value' => '/public/favicon.png',
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::TITLE->value,
            'setting_value' => 'فروشگاه',
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::DESCRIPTION->value,
            'setting_value' => 'وب سایت فروشگاهی',
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::KEYWORDS->value,
            'setting_value' => 'فروشگاه,سایت فروشگاهی,وب سایت فروشگاهی',
            'group_name' => SettingGroupsEnum::MAIN->value,
        ]);

        // sms group
        Setting::create([
            'name' => SettingsEnum::SMS_SIGNUP->value,
            'setting_value' => "{{shop}}" . "\n" . "خوش آمدید، خرید راحت و مطمئنی داشته باشید.",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_ACTIVATION->value,
            'setting_value' => "{{shop}}" . "\n" . "کد فعالسازی: {{code}}",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_RECOVER_PASS->value,
            'setting_value' => "{{shop}}" . "\n" . "کد فراموشی کلمه عبور: {{code}}",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_BUY->value,
            'setting_value' => "{{shop}}" . "\n"
                . "سفارش شما با کد {{order_code}} با موفقیت ثبت شد."
                . "\n"
                . "برای پیگیری سفارش می توانید به پروفایل کاربری، قسمت سفارش ها مراجعه فرمایید.",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_ORDER_STATUS->value,
            'setting_value' => "{{shop}}" . "\n"
                . "کاربر گرامی سفارش به شماره {{order_code}} در وضعیت {{status}} قرار گرفت.",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_RETURN_ORDER->value,
            'setting_value' => "{{shop}}" . "\n"
                . "سفارش بازگشتی با شماره {{return_code}} برای شما ثبت گردید."
                . "\n"
                . "برای اطلاعات بیشتر به سایت مراجعه نمایید.",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::SMS_RETURN_ORDER_STATUS->value,
            'setting_value' => "{{shop}}" . "\n"
                . "کاربر گرامی سفارش مرجوعی به شماره {{return_code}} در وضعیت {{status}} قرار گرفت.",
            'group_name' => SettingGroupsEnum::SMS->value,
        ]);

        // info group
        Setting::create([
            'name' => SettingsEnum::ADDRESS->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::INFO->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::PHONES->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::INFO->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::STORE_PROVINCE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::INFO->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::STORE_CITY->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::INFO->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::LAT_LNG->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::INFO->value,
        ]);

        // shop group
        Setting::create([
            'name' => SettingsEnum::DIVIDE_PAYMENT_PRICE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::SHOP->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::MIN_FREE_POST_PRICE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::SHOP->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::DEFAULT_POST_PRICE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::SHOP->value,
            'default_value' => 50000,
        ]);

        // social group
        Setting::create([
            'name' => SettingsEnum::SOCIALS->value,
            // json object => [[type => 'instagram', link => '#'], ...]
            'setting_value' => json_encode([]),
            'group_name' => SettingGroupsEnum::SOCIAL->value,
        ]);

        // general group
        Setting::create([
            'name' => SettingsEnum::PRODUCT_EACH_PAGE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::GENERAL->value,
            'default_value' => '15',
        ]);
        Setting::create([
            'name' => SettingsEnum::BLOG_EACH_PAGE->value,
            'setting_value' => '',
            'group_name' => SettingGroupsEnum::GENERAL->value,
            'default_value' => '15',
        ]);

        // footer group
        Setting::create([
            'name' => SettingsEnum::FOOTER_DESCRIPTION->value,
            'setting_value' => 'تیم هیوا، با ارائه با کیفیت‌ترین محصولات به شما',
            'group_name' => SettingGroupsEnum::FOOTER->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::FOOTER_COPYRIGHT->value,
            'setting_value' => 'کلیه حقوق این فروشگاه متعلق به شرکت هیوا می‌باشد و هرگونه کپی برداری از آن پیگرد قانونی دارد.',
            'group_name' => SettingGroupsEnum::FOOTER->value,
        ]);
        Setting::create([
            'name' => SettingsEnum::FOOTER_NAMADS->value,
            'setting_value' => json_encode([]),
            'group_name' => SettingGroupsEnum::FOOTER->value,
        ]);
    }
}
