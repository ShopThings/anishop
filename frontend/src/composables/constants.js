export const PRODUCT_ATTRIBUTE_TYPES = {
    MULTI_SELECT: {
        value: 'multi_select',
        text: 'چند انتخابی',
    },
    SINGLE_SELECT: {
        value: 'single_select',
        text: 'تک انتخابی',
    },
}

export const PRODUCT_ORDER_TYPES = {
    NEWEST: {
        value: 'newest',
        text: 'جدیدترین',
    },
    MOST_DISCOUNT: {
        value: 'most_discount',
        text: 'پرتخفیف ترین',
    },
    MOST_VIEWED: {
        value: 'most_viewed',
        text: 'پربازدیدترین',
    },
    LEAST_EXPENSIVE: {
        value: 'least_expensive',
        text: 'ارزانترین',
    },
    MOST_EXPENSIVE: {
        value: 'most_expensive',
        text: 'گرانترین',
    },
}

export const BLOG_ORDER_TYPES = {
    NEWEST: {
        value: 'newest',
        text: 'جدیدترین',
    },
    OLDEST: {
        value: 'oldest',
        text: 'قدیمی ترین',
    },
    MOST_VIEWED: {
        value: 'most_viewed',
        text: 'پربازدیدترین',
    },
}

export const SLIDER_PLACES = {
    MAIN: {
        value: 'main',
        text: 'اسلایدر اصلی',
    },
    MAIN_BLOG: {
        value: 'main_blog',
        text: 'اسلایدر اصلی بلاگ',
    },
    MAIN_BLOG_SIDE: {
        value: 'main_blog_side',
        text: 'اسلایدر اصلی بلاگ - اسلایدهای کناری',
    },
    MAIN_SLIDERS: {
        value: 'main_sliders',
        text: 'اسلایدرهای محصول',
    },
    MAIN_SLIDER_IMAGES: {
        value: 'main_slider_images',
        text: 'تصویر محصولات',
    },
    AMAZING_OFFER: {
        value: 'amazing_offer',
        text: 'پیشنهادهای شگفت‌انکیز',
    },
}

export const SLIDER_OPTIONS = {
    SHOW_ALL_LINK: {
        value: 'show_all_link',
    },
    BRAND_ID: {
        value: 'brand_id',
    },
    CATEGORY_ID: {
        value: 'category_id',
    },
    ORDER_BY: {
        value: 'order_by',
    },
    IS_SPECIAL: {
        value: 'is_special',
    },
    COUNT: {
        value: 'count',
    },
    BESIDE_IMAGES: {
        value: 'beside_images',
    },
}

export const RETURN_ORDER_STATUSES = {
    CHECKING: 'checking',
    DENIED_BY_USER: 'denied_by_user',
    ACCEPT: 'accept',
    DENIED: 'denied',
    SENDING: 'sending',
    RECEIVED: 'received',
    MONEY_RETURNED: 'money_returned',
}

export const COMMENT_STATUSES = {
    UNSET: {
        value: 'unset',
        text: 'در حال بررسی',
    },
    REJECTED: {
        value: 'rejected',
        text: 'عدم تایید',
    },
    ACCEPTED: {
        value: 'accepted',
        text: 'تایید شده',
    },
}

export const BLOG_VOTING_TYPES = {
    VOTED: 1,
    NOT_VOTED: 2,
    NOT_SET: 3,
}

export const SETTING_KEYS = {
    LOGO: 'logo',
    LOGO_LIGHT: 'logo_light',
    FAVICON: 'favicon',
    TITLE: 'title',
    DESCRIPTION: 'description',
    KEYWORDS: 'keywords',
    SMS_SIGNUP: 'sms_signup',
    SMS_ACTIVATION: 'sms_activation',
    SMS_RECOVER_PASS: 'sms_recover_pass',
    SMS_BUY: 'sms_buy',
    SMS_ORDER_STATUS: 'sms_order_status',
    SMS_WALLET_CHARGE: 'sms_wallet_charge',
    SMS_RETURN_ORDER: 'sms_return_order',
    SMS_RETURN_ORDER_STATUS: 'sms_return_order_status',
    ADDRESS: 'address',
    PHONES: 'phones',
    STORE_PROVINCE: 'store_province',
    STORE_CITY: 'store_city',
    LAT_LNG: 'lat_lng',
    MIN_FREE_POST_PRICE: 'min_free_post_price',
    PRODUCT_EACH_PAGE: 'product_each_page',
    BLOG_EACH_PAGE: 'blog_each_page',
    SOCIALS: 'socials',
    FOOTER_DESCRIPTION: 'footer_description',
    FOOTER_COPYRIGHT: 'footer_copyright',
    FOOTER_NAMADS: 'footer_namads',
    DEFAULT_IMAGE_PLACEHOLDER: 'default_image_placeholder',
    DEFAULT_POST_PRICE: 'default_post_price',
}

export const SETTING_GROUPS = {
    MAIN: 'main',
    SMS: 'sms',
    INFO: 'info',
    SHOP: 'shop',
    SOCIAL: 'social',
    GENERAL: 'general',
    FOOTER: 'footer',
}

export const SOCIAL_NETWORKS = {
    EMAIL: {
        value: 'email',
        text: 'ایمیل',
    },
    X: {
        value: 'x',
        text: 'اکس',
    },
    INSTAGRAM: {
        value: 'instagram',
        text: 'اینستاگرام',
    },
    TELEGRAM: {
        value: 'telegram',
        text: 'تلگرام',
    },
    WHATSAPP: {
        value: 'whatsapp',
        text: 'واتساپ',
    },
    FACEBOOK: {
        value: 'facebook',
        text: 'فیسبوک',
    },
    YOUTUBE: {
        value: 'youtube',
        text: 'یوتیوب',
    },
    LINKEDIN: {
        value: 'linked_in',
        text: 'لینکدین',
    },
}
