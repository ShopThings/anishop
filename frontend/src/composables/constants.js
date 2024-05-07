export const MAX_RESERVED_TIME = 7200;

export const PAYMENT_METHOD_TYPES = {
  BEHPARDAKHT: {
    value: 'behpardakht',
    text: 'درگاه بانک - به پرداخت',
    type: 'bank_gateway',
  },
  IDPAY: {
    value: 'idpay',
    text: 'درگاه بانک - آیدی پی',
    type: 'bank_gateway',
  },
  IRANKISH: {
    value: 'irankish',
    text: 'درگاه بانک - ایران کیش',
    type: 'bank_gateway',
  },
  PARSIAN: {
    value: 'parsian',
    text: 'درگاه بانک - تجارت الکترونیک پارسیان',
    type: 'bank_gateway',
  },
  SADAD: {
    value: 'sadad',
    text: 'درگاه بانک - سداد',
    type: 'bank_gateway',
  },
  SEPEHR: {
    value: 'sepehr',
    text: 'درگاه بانک - پرداخت الکترونیک سپهر',
    type: 'bank_gateway',
  },
  ZARINPAL: {
    value: 'zarinpal',
    text: 'درگاه بانک - زرین پال',
    type: 'bank_gateway',
  },
}

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
    is_creatable: false,
  },
  MAIN_BLOG: {
    value: 'main_blog',
    text: 'اسلایدر اصلی بلاگ',
    is_creatable: false,
  },
  MAIN_BLOG_SIDE: {
    value: 'main_blog_side',
    text: 'اسلایدر اصلی بلاگ - اسلایدهای کناری',
    is_creatable: false,
  },
  MAIN_SLIDERS: {
    value: 'main_sliders',
    text: 'اسلایدرهای محصول',
    is_creatable: true,
  },
  MAIN_SLIDER_IMAGES: {
    value: 'main_slider_images',
    text: 'تصویر محصولات',
    is_creatable: true,
  },
  AMAZING_OFFER: {
    value: 'amazing_offer',
    text: 'پیشنهادهای شگفت‌انگیز',
    is_creatable: false,
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

export const SLIDER_ITEM_OPTIONS = {
  IMAGE: {
    value: 'image',
  },
  LINK: {
    value: 'link',
  },
  PRODUCT_ID: {
    value: 'product_id',
  },
  BLOG_ID: {
    value: 'blog_id',
  },
}

export const MENU_PLACES = {
  TOP_MENU: {
    value: 'top_menu',
    text: 'منوی بالای صفحه',
  },
  TOP_MENU_BLOG: {
    value: 'top_menu_blog',
    text: 'منوی بالای صفحه بلاگ',
  },
  FOOTER: {
    value: 'footer',
    text: 'منوی فوتر/پانوشت',
  },
}

export const COMMENT_STATUSES = {
  UNSET: {
    value: 'unset',
    text: 'در حال بررسی',
    color_hex: '#06b6d4',
  },
  REJECTED: {
    value: 'rejected',
    text: 'عدم تایید',
    color_hex: '#ef4444',
  },
  ACCEPTED: {
    value: 'accepted',
    text: 'تایید شده',
    color_hex: '#10b981',
  },
}

export const COMMENT_SEEN_STATUSES = {
  UNREAD: {
    value: 'unread',
    text: 'خوانده نشده',
    color_hex: '#ef4444',
  },
  READ: {
    value: 'read',
    text: 'خوانده شده',
    color_hex: '#10b981',
  },
}

export const COMMENT_VOTED_TYPES = {
  VOTED: 1,
  NOT_VOTED: 2,
  NOT_SET: 3,
}

export const COMMENT_VOTING_TYPES = {
  LIKING: 1,
  UNDO_LIKING: 2,
  DISLIKING: 3,
  UNDO_DISLIKING: 4,
  FROM_LIKE_TO_DISLIKING: 5,
  FROM_DISLIKING_TO_LIKE: 6,
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
  DIVIDE_PAYMENT_PRICE: 'divide_payment_price',
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
    icon: '<svg viewBox="0 0 24 24" class="w-auto h-5" fill="none" stroke-width="1.5" stroke="currentColor"><path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
  },
  X: {
    value: 'x',
    text: 'اکس',
    icon: '<svg class="fill-current w-auto h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;" xml:space="preserve"><g><path d="M21.159809,2C21.623091,2,22,2.376909,22,2.840191v18.319618C22,21.623091,21.623091,22,21.159809,22H2.84019   C2.37691,22,2,21.623091,2,21.159809V2.840191C2,2.376909,2.37691,2,2.84019,2H21.159809 M21.159809,1H2.84019   C1.82388,1,1,1.823879,1,2.840191v18.319618C1,22.176121,1.82388,23,2.84019,23h18.319618C22.176121,23,23,22.176121,23,21.159809   V2.840191C23,1.823879,22.176121,1,21.159809,1L21.159809,1z"></path></g><path d="M13.523985,10.775623L19.480984,4h-1.41143l-5.174801,5.88195L8.764665,4H4l6.246901,8.895341L4,20h1.411431  l5.461361-6.21299L15.235336,20H20L13.523985,10.775623z M11.590199,12.973429l-0.633908-0.886331L5.920428,5.041171h2.168246  l4.065295,5.6884l0.631236,0.886331l5.283681,7.39365H15.90064L11.590199,12.973429z"></path></svg>',
  },
  INSTAGRAM: {
    value: 'instagram',
    text: 'اینستاگرام',
    icon: '<svg class="fill-current w-auto h-7" x="0px" y="0px" viewBox="0 0 32 32"><path d="M 11.46875 5 C 7.917969 5 5 7.914063 5 11.46875 L 5 20.53125 C 5 24.082031 7.914063 27 11.46875 27 L 20.53125 27 C 24.082031 27 27 24.085938 27 20.53125 L 27 11.46875 C 27 7.917969 24.085938 5 20.53125 5 Z M 11.46875 7 L 20.53125 7 C 23.003906 7 25 8.996094 25 11.46875 L 25 20.53125 C 25 23.003906 23.003906 25 20.53125 25 L 11.46875 25 C 8.996094 25 7 23.003906 7 20.53125 L 7 11.46875 C 7 8.996094 8.996094 7 11.46875 7 Z M 21.90625 9.1875 C 21.402344 9.1875 21 9.589844 21 10.09375 C 21 10.597656 21.402344 11 21.90625 11 C 22.410156 11 22.8125 10.597656 22.8125 10.09375 C 22.8125 9.589844 22.410156 9.1875 21.90625 9.1875 Z M 16 10 C 12.699219 10 10 12.699219 10 16 C 10 19.300781 12.699219 22 16 22 C 19.300781 22 22 19.300781 22 16 C 22 12.699219 19.300781 10 16 10 Z M 16 12 C 18.222656 12 20 13.777344 20 16 C 20 18.222656 18.222656 20 16 20 C 13.777344 20 12 18.222656 12 16 C 12 13.777344 13.777344 12 16 12 Z"></path></svg>',
  },
  TELEGRAM: {
    value: 'telegram',
    text: 'تلگرام',
    icon: '<svg class="fill-current w-auto h-7" x="0px" y="0px" viewBox="0 0 64 64"><path d="M 32 10 C 19.85 10 10 19.85 10 32 C 10 44.15 19.85 54 32 54 C 44.15 54 54 44.15 54 32 C 54 19.85 44.15 10 32 10 z M 32 14 C 41.941 14 50 22.059 50 32 C 50 41.941 41.941 50 32 50 C 22.059 50 14 41.941 14 32 C 14 22.059 22.059 14 32 14 z M 41.041016 23.337891 C 40.533078 23.279297 39.894891 23.418531 39.181641 23.675781 C 37.878641 24.145781 21.223719 31.217953 20.261719 31.626953 C 19.350719 32.014953 18.487328 32.437828 18.486328 33.048828 C 18.486328 33.478828 18.741312 33.721656 19.445312 33.972656 C 20.177313 34.234656 22.023281 34.79275 23.113281 35.09375 C 24.163281 35.38275 25.357344 35.130844 26.027344 34.714844 C 26.736344 34.273844 34.928625 28.7925 35.515625 28.3125 C 36.102625 27.8325 36.571797 28.448688 36.091797 28.929688 C 35.611797 29.410688 29.988094 34.865094 29.246094 35.621094 C 28.346094 36.539094 28.985844 37.490094 29.589844 37.871094 C 30.278844 38.306094 35.239328 41.632016 35.986328 42.166016 C 36.733328 42.700016 37.489594 42.941406 38.183594 42.941406 C 38.877594 42.941406 39.242891 42.026797 39.587891 40.966797 C 39.992891 39.725797 41.890047 27.352062 42.123047 24.914062 C 42.194047 24.175062 41.960906 23.683844 41.503906 23.464844 C 41.365656 23.398594 41.210328 23.357422 41.041016 23.337891 z"></path></svg>',
  },
  WHATSAPP: {
    value: 'whatsapp',
    text: 'واتساپ',
    icon: '<svg class="fill-current w-auto h-7" x="0px" y="0px" viewBox="0 0 32 32"><path fill-rule="evenodd" d="M 24.503906 7.503906 C 22.246094 5.246094 19.246094 4 16.050781 4 C 9.464844 4 4.101563 9.359375 4.101563 15.945313 C 4.097656 18.050781 4.648438 20.105469 5.695313 21.917969 L 4 28.109375 L 10.335938 26.445313 C 12.078125 27.398438 14.046875 27.898438 16.046875 27.902344 L 16.050781 27.902344 C 22.636719 27.902344 27.996094 22.542969 28 15.953125 C 28 12.761719 26.757813 9.761719 24.503906 7.503906 Z M 16.050781 25.882813 L 16.046875 25.882813 C 14.265625 25.882813 12.515625 25.402344 10.992188 24.5 L 10.628906 24.285156 L 6.867188 25.269531 L 7.871094 21.605469 L 7.636719 21.230469 C 6.640625 19.648438 6.117188 17.820313 6.117188 15.945313 C 6.117188 10.472656 10.574219 6.019531 16.054688 6.019531 C 18.707031 6.019531 21.199219 7.054688 23.074219 8.929688 C 24.949219 10.808594 25.980469 13.300781 25.980469 15.953125 C 25.980469 21.429688 21.523438 25.882813 16.050781 25.882813 Z M 21.496094 18.445313 C 21.199219 18.296875 19.730469 17.574219 19.457031 17.476563 C 19.183594 17.375 18.984375 17.328125 18.785156 17.625 C 18.585938 17.925781 18.015625 18.597656 17.839844 18.796875 C 17.667969 18.992188 17.492188 19.019531 17.195313 18.871094 C 16.894531 18.722656 15.933594 18.40625 14.792969 17.386719 C 13.90625 16.597656 13.304688 15.617188 13.132813 15.320313 C 12.957031 15.019531 13.113281 14.859375 13.261719 14.710938 C 13.398438 14.578125 13.5625 14.363281 13.710938 14.1875 C 13.859375 14.015625 13.910156 13.890625 14.011719 13.691406 C 14.109375 13.492188 14.058594 13.316406 13.984375 13.167969 C 13.910156 13.019531 13.3125 11.546875 13.0625 10.949219 C 12.820313 10.367188 12.574219 10.449219 12.390625 10.4375 C 12.21875 10.429688 12.019531 10.429688 11.820313 10.429688 C 11.621094 10.429688 11.296875 10.503906 11.023438 10.804688 C 10.75 11.101563 9.980469 11.824219 9.980469 13.292969 C 9.980469 14.761719 11.050781 16.183594 11.199219 16.382813 C 11.347656 16.578125 13.304688 19.59375 16.300781 20.886719 C 17.011719 21.195313 17.566406 21.378906 18 21.515625 C 18.714844 21.742188 19.367188 21.710938 19.882813 21.636719 C 20.457031 21.550781 21.648438 20.914063 21.898438 20.214844 C 22.144531 19.519531 22.144531 18.921875 22.070313 18.796875 C 21.996094 18.671875 21.796875 18.597656 21.496094 18.445313 Z"></path></svg>',
  },
  FACEBOOK: {
    value: 'facebook',
    text: 'فیسبوک',
    icon: '<svg class="fill-current w-auto h-5" viewBox="0 0 8 16"><path d="M7.43902 6.4H6.19918H5.75639V5.88387V4.28387V3.76774H6.19918H7.12906C7.3726 3.76774 7.57186 3.56129 7.57186 3.25161V0.516129C7.57186 0.232258 7.39474 0 7.12906 0H5.51285C3.76379 0 2.54609 1.44516 2.54609 3.5871V5.83226V6.34839H2.10329H0.597778C0.287819 6.34839 0 6.63226 0 7.04516V8.90323C0 9.26452 0.243539 9.6 0.597778 9.6H2.05902H2.50181V10.1161V15.3032C2.50181 15.6645 2.74535 16 3.09959 16H5.18075C5.31359 16 5.42429 15.9226 5.51285 15.8194C5.60141 15.7161 5.66783 15.5355 5.66783 15.3806V10.1419V9.62581H6.13276H7.12906C7.41688 9.62581 7.63828 9.41935 7.68256 9.10968V9.08387V9.05806L7.99252 7.27742C8.01466 7.09677 7.99252 6.89032 7.85968 6.68387C7.8154 6.55484 7.61614 6.42581 7.43902 6.4Z"></path></svg>',
  },
  YOUTUBE: {
    value: 'youtube',
    text: 'یوتیوب',
    icon: '<svg class="fill-current w-auto h-4" viewBox="0 0 16 12"><path d="M15.6645 1.88018C15.4839 1.13364 14.9419 0.552995 14.2452 0.359447C13.0065 6.59222e-08 8 0 8 0C8 0 2.99355 6.59222e-08 1.75484 0.359447C1.05806 0.552995 0.516129 1.13364 0.335484 1.88018C0 3.23502 0 6 0 6C0 6 0 8.79263 0.335484 10.1198C0.516129 10.8664 1.05806 11.447 1.75484 11.6406C2.99355 12 8 12 8 12C8 12 13.0065 12 14.2452 11.6406C14.9419 11.447 15.4839 10.8664 15.6645 10.1198C16 8.79263 16 6 16 6C16 6 16 3.23502 15.6645 1.88018ZM6.4 8.57143V3.42857L10.5548 6L6.4 8.57143Z"></path></svg>',
  },
  LINKEDIN: {
    value: 'linked_in',
    text: 'لینکدین',
    icon: '<svg class="fill-current w-auto h-4" viewBox="0 0 14 14"><path d="M13.0214 0H1.02084C0.453707 0 0 0.451613 0 1.01613V12.9839C0 13.5258 0.453707 14 1.02084 14H12.976C13.5432 14 13.9969 13.5484 13.9969 12.9839V0.993548C14.0422 0.451613 13.5885 0 13.0214 0ZM4.15142 11.9H2.08705V5.23871H4.15142V11.9ZM3.10789 4.3129C2.42733 4.3129 1.90557 3.77097 1.90557 3.11613C1.90557 2.46129 2.45002 1.91935 3.10789 1.91935C3.76577 1.91935 4.31022 2.46129 4.31022 3.11613C4.31022 3.77097 3.81114 4.3129 3.10789 4.3129ZM11.9779 11.9H9.9135V8.67097C9.9135 7.90323 9.89082 6.8871 8.82461 6.8871C7.73571 6.8871 7.57691 7.74516 7.57691 8.60323V11.9H5.51254V5.23871H7.53154V6.16452H7.55423C7.84914 5.62258 8.50701 5.08065 9.52785 5.08065C11.6376 5.08065 12.0232 6.43548 12.0232 8.2871V11.9H11.9779Z"></path></svg>',
  },
}

export const REPORT_PERIODS = {
  TODAY: {
    value: 'today',
    text: 'امروز',
  },
  WEEKLY: {
    value: 'weekly',
    text: 'هفته اخیر',
  },
  MONTHLY: {
    value: 'monthly',
    text: 'این ماه',
  },
  MONTHS_3: {
    value: 'months_3',
    text: 'سه ماهه اخیر',
  },
  MONTHS_6: {
    value: 'months_6',
    text: 'شش ماهه اخیر',
  },
  YEARLY: {
    value: 'yearly',
    text: 'امسال',
  },
}
