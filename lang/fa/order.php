<?php

use App\Enums\Orders\ReturnOrderStatusesEnum;

return [

    'return' => [
        'status' => [
            ReturnOrderStatusesEnum::CHECKING->value => 'در حال بررسی',
            ReturnOrderStatusesEnum::DENIED_BY_USER->value => 'لغو توسط کاربر',
            ReturnOrderStatusesEnum::ACCEPT->value => 'قبول درخواست',
            ReturnOrderStatusesEnum::DENIED->value => 'رد درخواست',
            ReturnOrderStatusesEnum::SENDING->value => 'ارسال مرسولات توسط کاربر',
            ReturnOrderStatusesEnum::RECEIVED->value => 'دریافت مرسولات توسط پذیرنده',
            ReturnOrderStatusesEnum::RETURN_TO_USER->value => 'بازگشت مرسولات توسط پذیرنده',
            ReturnOrderStatusesEnum::RECEIVED_BY_USER->value => 'دریافت مرسولات ارسال شده توسط پذیرنده',
            ReturnOrderStatusesEnum::MONEY_RETURNED->value => 'بازگشت وجه پرداخت شده به کاربر',
        ],
    ],

];
