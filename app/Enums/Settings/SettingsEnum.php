<?php

namespace App\Enums\Settings;

use App\Traits\EnumTranslateTrait;
use BackedEnum;

enum SettingsEnum: string
{
    use EnumTranslateTrait;

    case LOGO = 'logo';
    case LOGO_LIGHT = 'logo_light';
    case FAVICON = 'favicon';

    case TITLE = 'title';
    case DESCRIPTION = 'description';
    case KEYWORDS = 'keywords';

    case SMS_SIGNUP = 'sms_signup';
    case SMS_OTP = 'sms_otp';
    case SMS_ACTIVATION = 'sms_activation';
    case SMS_RECOVER_PASS = 'sms_recover_pass';
    case SMS_BUY = 'sms_buy';
    case SMS_ORDER_STATUS = 'sms_order_status';
    case SMS_WALLET_CHARGE = 'sms_wallet_charge';
    case SMS_RETURN_ORDER = 'sms_return_order';
    case SMS_RETURN_ORDER_STATUS = 'sms_return_order_status';

    case ADDRESS = 'address';
    case PHONES = 'phones';
    case STORE_PROVINCE = 'store_province';
    case STORE_CITY = 'store_city';
    case LAT_LNG = 'lat_lng';

    case DIVIDE_PAYMENT_PRICE = 'divide_payment_price';
    case MIN_FREE_POST_PRICE = 'min_free_post_price';

    case PRODUCT_EACH_PAGE = 'product_each_page';
    case BLOG_EACH_PAGE = 'blog_each_page';

    case SOCIALS = 'socials';
    case FOOTER_DESCRIPTION = 'footer_description';
    case FOOTER_COPYRIGHT = 'footer_copyright';
    case FOOTER_NAMADS = 'footer_namads';

    case DEFAULT_IMAGE_PLACEHOLDER = 'default_image_placeholder';

    case DEFAULT_POST_PRICE = 'default_post_price';

    /**
     * @return array<BackedEnum>
     */
    public static function getGeneralSettings(): array
    {
        return [
            self::TITLE,
            self::DESCRIPTION,
            self::KEYWORDS,
            self::ADDRESS,
            self::PHONES,
            self::STORE_PROVINCE,
            self::STORE_CITY,
            self::LAT_LNG,
            self::PRODUCT_EACH_PAGE,
            self::BLOG_EACH_PAGE,
            self::SOCIALS,
            self::FOOTER_DESCRIPTION,
            self::FOOTER_COPYRIGHT,
            self::FOOTER_NAMADS,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function translationArray(): array
    {
        return [
            self::LOGO->value => 'تصویر لوگو',
            self::LOGO_LIGHT->value => 'تصویر لوگو روشن',
            self::FAVICON->value => 'تصویر فاو آیکون',
            self::TITLE->value => 'عنوان سایت',
            self::DESCRIPTION->value => 'توضیحات سایت',
            self::KEYWORDS->value => 'کلمات کلیدی سایت',
            self::SMS_SIGNUP->value => 'پیامک ثبت نام',
            self::SMS_OTP->value => 'پیامک رمز یکبار مصرف',
            self::SMS_ACTIVATION->value => 'پیامک فعالسازی اکانت',
            self::SMS_RECOVER_PASS->value => 'پیامک بازیابی کلمه عبور',
            self::SMS_BUY->value => 'پیامک ثبت سفارش',
            self::SMS_ORDER_STATUS->value => 'پیامک تغییر وضعیت سفارش',
            self::SMS_WALLET_CHARGE->value => 'پیامک شارژ کیف پول',
            self::SMS_RETURN_ORDER->value => 'پیامک ثبت مرجوع سفارش',
            self::SMS_RETURN_ORDER_STATUS->value => 'پیامک تغییر وضعیت سفارش مرجوعی',
            self::ADDRESS->value => 'آدرس محل کسب و کار',
            self::PHONES->value => 'شماره تلفن‌های تماس',
            self::STORE_PROVINCE->value => 'استان محل فروشگاه',
            self::STORE_CITY->value => 'شهر محل فروشگاه',
            self::LAT_LNG->value => 'موقعیت جغرافیایی محل فروشگاه',
            self::DIVIDE_PAYMENT_PRICE->value => 'بیشترین مبلغ قابل پرداخت در هر تراکنش',
            self::MIN_FREE_POST_PRICE->value => 'مبلغ رایگان نمودن هزینه ارسال',
            self::PRODUCT_EACH_PAGE->value => 'تعداد محصول در هر صفحه',
            self::BLOG_EACH_PAGE->value => 'تعداد بلاگ در هر صفحه',
            self::SOCIALS->value => 'شبکه‌های اجتماعی',
            self::FOOTER_DESCRIPTION->value => 'توضیحات فوتر/پانوشت',
            self::FOOTER_COPYRIGHT->value => 'حق کپی رایت فوتر/پانوشت',
            self::FOOTER_NAMADS->value => 'نمادهای فوتر/پانوشت',
            self::DEFAULT_IMAGE_PLACEHOLDER->value => 'تصویر پیش از بارگذاری تصویر اصلی',
            self::DEFAULT_POST_PRICE->value => 'هزینه ارسال پیش‌فرض',
        ];
    }
}
