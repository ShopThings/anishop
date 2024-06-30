<?php

use App\Enums\Times\TimesEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | Use semantic versioning for it.
    |
    | @see https://semver.org for more
    |
    */

    'version' => '0.1.0',

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    |
    | Some general settings will define in this place
    |
    */

    'frontend_url' => env('APP_URL_FRONTEND', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Title Delimiter
    |--------------------------------------------------------------------------
    |
    | This will use for header meta titles to separate contexts
    |
    */

    'title_delimiter' => '-',

    /*
    |--------------------------------------------------------------------------
    | Money Unit
    |--------------------------------------------------------------------------
    |
    | This will use for local places
    |
    */

    'default_money_unit' => 'تومان',

    'money_unit' => [

        'en' => '$',

        'fa' => 'تومان',

    ],

    /**
     * This will set on fly
     */
    'current_money_unit' => '???',

    /*
    |--------------------------------------------------------------------------
    | Token Name Configuration
    |--------------------------------------------------------------------------
    |
    | Set token names for "main" pages and "admin" pages.
    |
    | NOTE:
    |     - Of course these names should concatenate with an info of user to be unique.
    |
    */

    'token_name' => [

        'main' => 'main_page_token_',

        'admin' => 'admin_page_token_',

    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Namespace Configuration
    |--------------------------------------------------------------------------
    |
    | Set cart names for "default" pages and "wishlist" namespace.
    |
    */

    'cart_name' => [

        /**
         * This cart instance won't save
         */
        'default' => 'shopping',

        /**
         * But this will save in DB with user's identifier (in this case the username)
         */
        'wishlist' => 'wishlist',

    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Configurations
    |--------------------------------------------------------------------------
    |
    */

    'sms' => [

        /**
         * Length of verification code
         */
        'verify_code_length' => 6,

        /**
         * Number of minutes to expire an OTP code
         */
        'otp_code_expire_time' => 10,

        /**
         * Number of minutes to wait before user can request for another code
         */
        'verify_code_resend_wait' => 1,

        /**
         * These variables are for customizing sms contents,
         * to show user specific information.
         *
         * Note: DO NOT CHANGE KEYS, PLEASE
         */
        'replacements' => [

            'username' => '{{username}}',

            'first_name' => '{{first_name}}',

            'code' => '{{code}}',

            'order_code' => '{{orderCode}}',

            'status' => '{{status}}',

            'shop' => '{{shop}}',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Order Configurations
    |--------------------------------------------------------------------------
    |
    */

    'order' => [

        /**
         * Prefix of factors
         *
         * Note:
         *   - Be careful with length of it and check how much is order's code max length in DB.
         *   - For now I used nanoid of default size (21 characters) and (25) length for code in DB
         *     which means my prefix should be (4) at max.
         *
         * @example A factor code like 123456789,
         *          will be something like AK-123456789 for better look
         */
        'factor_prefix' => 'AK-',

        /**
         * Maximum time of reserving a partially paid order
         */
        'max_reservation_time' => TimesEnum::HOURS_2->value,

        /**
         * What type of reservation time should consider in order reservation
         */
        'reservation_time' => 'dynamic',

        /**
         * Time to reserve products for pay,
         * after this time, products will return to stock.
         */
        'reservation_times' => [

            /**
             * If number of payment chunks is not important, use this one
             */
            'static' => TimesEnum::MINUTES_20->value,

            /**
             * But if number of chunks should be considered for products reservation time, use this one
             */
            'dynamic' => [
                1 => TimesEnum::MINUTES_10->value,
                2 => TimesEnum::MINUTES_20->value,
                'n' => TimesEnum::MINUTES_30->value,
            ],

        ],

        /**
         * Because frontend is separated from backend, gateway results are difficult to handle in frontend
         * therefor we use a proxy in backend and pass result by redirect to frontend,
         * so we need url to redirect right?🤨 (this is the usage of provided url😊)
         */
        'gateway_proxy_callback_url' => env('GATEWAY_PROXY_BACK_URL', 'http://localhost/purchase-result'),

    ],

    /*
    |--------------------------------------------------------------------------
    | User Configurations
    |--------------------------------------------------------------------------
    |
    */

    'user' => [

        /**
         * Number of address that user can store in his profile
         */
        'max_address_count' => 3,

        /**
         * Number of cart that user can store in his carts place
         */
        'max_cart_count' => 1,

    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Configurations
    |--------------------------------------------------------------------------
    |
    */

    'sitemap_destination' => env('SITEMAP_DESTINATION'),

];
