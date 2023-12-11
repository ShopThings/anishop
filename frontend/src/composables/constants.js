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
