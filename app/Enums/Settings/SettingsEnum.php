<?php

namespace App\Enums\Settings;

enum SettingsEnum: string
{
    case LOGO = 'logo';
    case LOGO_LIGHT = 'logo_light';
    case FAVICON = 'favicon';

    case TITLE = 'title';
    case DESCRIPTION = 'description';
    case KEYWORDS = 'keywords';

    case SMS_SIGNUP = 'sms_signup';
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

    case MIN_FREE_POST_PRICE = 'min_free_post_price';

    case PRODUCT_EACH_PAGE = 'product_each_page';
    case BLOG_EACH_PAGE = 'blog_each_page';

    case SOCIALS = 'socials';
    case FOOTER_DESCRIPTION = 'footer_description';
    case FOOTER_COPYRIGHT = 'footer_copyright';
    case FOOTER_NAMADS = 'footer_namads';

    case DEFAULT_IMAGE_PLACEHOLDER = 'default_image_placeholder';

    case DEFAULT_POST_PRICE = 'default_post_price';
}
