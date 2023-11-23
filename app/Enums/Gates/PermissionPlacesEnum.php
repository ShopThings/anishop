<?php

namespace App\Enums\Gates;

enum PermissionPlacesEnum: string
{
    case ADDRESS_USER = 'address_user';
    case BLOG = 'blog';
    case BLOG_CATEGORY = 'blog_category';
    case BLOG_COMMENT = 'blog_comment';
    case BLOG_COMMENT_BADGE = 'blog_comment_badge';
    case BRAND = 'brand';
    case CATEGORY = 'category';
    case CATEGORY_IMAGE = 'category_image';
    case CITY_POST_PRICE = 'city_post_price';
    case COLOR = 'color';
    case COMPLAINT = 'complaint';
    case CONTACT_US = 'contact_us';
    case COUPON = 'coupon';
    case FAQ = 'faq';
    case FESTIVAL = 'festival';
    case MENU = 'menu';
    case NEWSLETTER = 'newsletter';
    case ORDER = 'order';
    case ORDER_BADGE = 'order_badge';
    case PAYMENT_METHOD = 'payment_method';
    case PRODUCT = 'product';
    case PRODUCT_COMMENT = 'product_comment';
    case PRODUCT_ATTRIBUTE = 'product_attribute';
    case RETURN_ORDER_REQUEST = 'return_order_request';
    case SETTING = 'setting';
    case SLIDER = 'slider';
    case SMS_LOG = 'sms_log';
    case STATIC_PAGE = 'static_page';
    case UNIT = 'unit';
    case USER = 'user';
    case USER_FAVORITE_PRODUCT = 'user_favorite_product';
    case USER_NOTIFICATION = 'user_notification';
    case WEIGHT_POST_PRICE = 'weight_post_price';
    case FILE_MANAGER = 'file_manager';
}
