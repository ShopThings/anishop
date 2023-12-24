<?php

namespace App\Repositories;

use App\Enums\DatabaseEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\QB\InputTypesEnum;
use App\Enums\QB\TypesEnum;
use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * It'll return an array with below format: <br>
     *   <pre>
     *     [
     *       [
     *         value: '', // name of column (required)
     *         type: 'string', // valid type from defined types in "TypesEnum" enum class
     *         input: [
     *           text: 'username', // text of input's label (optional)
     *           placeholder: 'enter username', // placeholder of input (optional)
     *           type: 'text', // valid input type from defined types in "InputTypesEnum" enum class
     *           value: '', // input's value - (accepts "array", "object" and simple "text")
     *           value2: '', // input's value (for range types) - (accepts "array", "object" and simple "text")
     *           initialValue: '', // initial value (optional)
     *           initialValue2: '', // initial value (optional)
     *           min: 0, // min of input in number base inputs (optional)
     *           max: 1000, // max of input in number base inputs (optional)
     *           textKey: 'text', // text key of option to show to user (Needed with "options")
     *           key: 'value', // key of option to send back and check against(Needed with "options")
     *           options: [[text: 'a', key: 1], ...], // options of mostly for select inputs
     *           loading: false, // should load from server (optional) - boolean
     *           remoteUrl: '', // remote url for load "options" (optional - if it needs to search a bunch of stuffs, better to make it searchable)
     *         ]
     *       ],
     *       ...
     *     ]
     *   </pre>
     *
     * @template Sample You can copy sample code to use:
     * <pre>
     * [
     *   [
     *     'value' => '',
     *     'type' => '',
     *     'input' => [
     *       'text' => '',
     *       'placeholder' => '',
     *       'type' => '',
     *       'value' => '',
     *       'value2' => '',
     *       'initialValue' => '',
     *       'initialValue2' => '',
     *       'min' => 0,
     *       'max' => 0,
     *       'textKey' => '',
     *       'key' => '',
     *       'options' => [],
     *       'loading' => false,
     *       'remoteUrl' => '',
     *     ]
     *   ],
     * ]
     * </pre>
     *
     * @inheritDoc
     */
    public function getUsersQueryBuilderInfo(): array
    {
        $info = [];

        foreach (
            [
                'username' => 'نام کاربری',
                'first_name' => 'نام',
                'last_name' => 'نام خانوادگی',
                'national_code' => 'کد ملی',
                'shaba_number' => 'شماره شبا',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::TEXT->value,
                ],
            ];
        }

        foreach (
            [
                'is_admin' => 'کاربر ادمین',
                'is_banned' => 'کاربر بن شده',
                'is_verified' => 'کاربر تایید شده',
                'is_deleted' => 'کاربر حذف شده',
            ] as $value => $text) {
            $info[] = [
                'type' => TypesEnum::BOOLEAN->value,
                'input' => [
                    'value' => $value,
                    'text' => $text,
                    'placeholder' => 'انتخاب کنید',
                    'type' => InputTypesEnum::SELECT->value,
                    'textKey' => 'text',
                    'key' => 'value',
                    'options' => [
                        [
                            'text' => 'خیر',
                            'value' => DatabaseEnum::DB_NO,
                        ],
                        [
                            'text' => 'بله',
                            'value' => DatabaseEnum::DB_YES,
                        ],
                    ],
                ],
            ];
        }

        return $info;
    }

    /**
     * @see getUsersQueryBuilderInfo() method for more info
     * @inheritDoc
     */
    public function getProductsQueryBuilderInfo(): array
    {
        $info = [
            [
                'value' => 'brand',
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => 'برند',
                    'placeholder' => 'انتخاب نمایید',
                    'type' => InputTypesEnum::MULTISELECT->value,
                    'textKey' => 'name',
                    'key' => 'id',
                    'options' => [],
                    'loading' => true,
                    'remoteUrl' => route('admin.brands.index'),
                ],
            ],
            [
                'value' => 'category',
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => 'دسته‌بندی',
                    'placeholder' => 'انتخاب نمایید',
                    'type' => InputTypesEnum::MULTISELECT->value,
                    'textKey' => 'name',
                    'key' => 'id',
                    'options' => [],
                    'loading' => true,
                    'remoteUrl' => route('admin.categories.index'),
                ],
            ],
        ];

        foreach (
            [
                'title' => 'عنوان',
                'unit_name' => 'واحد شمارش',
                'color_name' => 'رنگ',
                'size' => 'سایز',
                'gurantee' => 'گارانتی',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::TEXT->value,
                ],
            ];
        }

        foreach (
            [
                'is_spacial' => 'محصول ویژه',
                'is_available' => 'موجود بودن محصول',
                'is_each_available' => 'موجود بودن هر محصول',
                'is_commenting_allowed' => 'دارای اجازه ارسال نظر',
                'is_published' => 'وضعیت انتشار',
                'is_each_published' => 'وضعیت انتشار هر محصول',
                'is_each_show_coming_soon' => 'نمایش بزودی در هر محصول',
                'is_each_show_call_for_more' => 'نمایش اطلاعات بیشتر در هر محصول',
                'is_deleted' => 'محصول حذف شده',
            ] as $value => $text) {
            $info[] = [
                'type' => TypesEnum::BOOLEAN->value,
                'input' => [
                    'value' => $value,
                    'text' => $text,
                    'placeholder' => 'انتخاب کنید',
                    'type' => InputTypesEnum::SELECT->value,
                    'textKey' => 'text',
                    'key' => 'value',
                    'options' => [
                        [
                            'text' => 'خیر',
                            'value' => DatabaseEnum::DB_NO,
                        ],
                        [
                            'text' => 'بله',
                            'value' => DatabaseEnum::DB_YES,
                        ],
                    ],
                ],
            ];
        }

        foreach (
            [
                'price' => 'قیمت',
                'discounted_price' => 'قیمت با تخفیف',
                'tax_rate' => 'مالیات بر ارزش افزوده',
                'stock_count' => 'تعداد موجود در انبار',
                'max_cart_count' => 'تعداد قابل خرید در یک سفارش',
                'weight' => 'وزن',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::NUMBER->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::NUMBER->value,
                    'min' => 0,
                ],
            ];
        }

        $info[] = [
            'value' => 'discounted_until',
            'type' => TypesEnum::DATE_OR_TIME_OR_BOTH->value,
            'input' => [
                'text' => 'تخفیف تا تاریخ',
                'placeholder' => 'انتخاب نمایید',
                'type' => InputTypesEnum::DATETIME->value,
            ],
        ];

        return $info;
    }

    /**
     * @see getUsersQueryBuilderInfo() method for more info
     * @inheritDoc
     */
    public function getOrdersQueryBuilderInfo(): array
    {
        $info = [
            [
                'value' => 'user',
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => 'کاربر',
                    'placeholder' => 'انتخاب نمایید',
                    'type' => InputTypesEnum::MULTISELECT->value,
                    'textKey' => 'username',
                    'key' => 'id',
                    'options' => [],
                    'loading' => true,
                    'remoteUrl' => route('admin.users.index'),
                ],
            ],
        ];

        foreach (
            [
                'code' => 'کد سفارش',
                'first_name' => 'نام',
                'last_name' => 'نام خانوادگی',
                'national_code' => 'کد ملی',
                'mobile' => 'شماره/نام کاربری خریدار',
                'province' => 'استان',
                'city' => 'شهرستان',
                'postal_code' => 'کد پستی',
                'receiver_name' => 'نام گیرنده',
                'receiver_mobile' => 'شماره تماس گیرنده',
                'coupon_code' => 'کد کوپن',
                'send_status_title' => 'وضعیت سفارش',
                'payment_method_title' => 'عنوان روش پرداخت',
                'product_title' => 'عنوان محصول',
                'color_name' => 'رنگ محصول',
                'size' => 'سایز محصول',
                'guarantee' => 'گارانتی محصول',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::TEXT->value,
                ],
            ];
        }

        foreach (
            [
                'address' => 'آدرس',
                'description' => 'توضیحات اضافی',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::STRING->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::TEXTAREA->value,
                ],
            ];
        }

        foreach (
            [
                'coupon_price' => 'قیمت کوپن',
                'shipping_price' => 'هزینه ارسال',
                'discount_price' => 'مبلغ تخفیف',
                'final_price' => 'مبلغ نهایی',
                'total_price' => 'مبلغ کل',
                'weight' => 'وزن محصول',
                'unit_price' => 'قیمت تکی محصول',
                'quantity' => 'تعداد محصول',
            ] as $value => $text) {
            $info[] = [
                'value' => $value,
                'type' => TypesEnum::NUMBER->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'وارد نمایید',
                    'type' => InputTypesEnum::NUMBER->value,
                    'min' => 0,
                ],
            ];
        }

        foreach (
            [
                'send_status_changed_at' => 'تاریخ تغییر وضعیت سفارش',
                'ordered_at' => 'تاریخ ثبت سفارش',
            ] as $value => $text) {

            $info[] = [
                'value' => $value,
                'type' => TypesEnum::DATE_OR_TIME_OR_BOTH->value,
                'input' => [
                    'text' => $text,
                    'placeholder' => 'انتخاب نمایید',
                    'type' => InputTypesEnum::DATETIME->value,
                ],
            ];
        }

        foreach (
            [
                'is_needed_factor' => 'درخواست فاکتور',
                'is_in_place_delivery' => 'دریافت حضوری',
                'is_returned' => 'محصول بازگشتی',
            ] as $value => $text) {
            $info[] = [
                'type' => TypesEnum::BOOLEAN->value,
                'input' => [
                    'value' => $value,
                    'text' => $text,
                    'placeholder' => 'انتخاب کنید',
                    'type' => InputTypesEnum::SELECT->value,
                    'textKey' => 'text',
                    'key' => 'value',
                    'options' => [
                        [
                            'text' => 'خیر',
                            'value' => DatabaseEnum::DB_NO,
                        ],
                        [
                            'text' => 'بله',
                            'value' => DatabaseEnum::DB_YES,
                        ],
                    ],
                ],
            ];
        }

        $info[] = [
            'value' => 'payment_method_type',
            'type' => TypesEnum::STRING->value,
            'input' => [
                'text' => 'نوع روش پرداخت',
                'placeholder' => 'انتخاب نمایید',
                'type' => InputTypesEnum::MULTISELECT->value,
                'textKey' => 'text',
                'key' => 'value',
                'options' => array_map(fn($item) => [
                    'text' => PaymentTypesEnum::getTranslations($item),
                    'value' => $item->value,
                ], [PaymentTypesEnum::BANK_GATEWAY]),
            ],
        ];

        $info[] = [
            'value' => 'payment_status',
            'type' => TypesEnum::STRING->value,
            'input' => [
                'text' => 'وضعیت پرداخت',
                'placeholder' => 'انتخاب نمایید',
                'type' => InputTypesEnum::MULTISELECT->value,
                'textKey' => 'text',
                'key' => 'value',
                'options' => array_map(fn($item) => [
                    'text' => PaymentStatusesEnum::getTranslations($item),
                    'value' => $item->value,
                ], PaymentTypesEnum::cases()),
            ],
        ];

        // because of multiple payments, it's good to have a filter to get
        // all orders that has been fully paid.
        $info[] = [
            'type' => TypesEnum::BOOLEAN->value,
            'input' => [
                'value' => 'has_full_payment',
                'text' => 'پرداخت کامل انجام شده',
                'placeholder' => 'انتخاب کنید',
                'type' => InputTypesEnum::SELECT->value,
                'textKey' => 'text',
                'key' => 'value',
                'options' => [
                    [
                        'text' => 'خیر',
                        'value' => DatabaseEnum::DB_NO,
                    ],
                    [
                        'text' => 'بله',
                        'value' => DatabaseEnum::DB_YES,
                    ],
                ],
            ],
        ];

        return $info;
    }
}
