<?php

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Times\TimesEnum;
use App\Support\Gate\PermissionHelper;

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

        'default' => 'shopping',

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
         * Time to reserve products for pay,
         * after this time, products will return to stock.
         */
        'reservation_time' => TimesEnum::MINUTES_10->value,

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
        'max_cart_count' => 2,

    ],

];
