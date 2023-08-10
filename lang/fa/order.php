<?php

use App\Enums\Orders\ReturnOrderStatusesEnum;

return [

    'return' => [
        'status' => [
            ReturnOrderStatusesEnum::CHECKING->value => 'در حال بررسی',
            ReturnOrderStatusesEnum::DENIED_BY_USER->value => 'لغو توسط کاربر',
            ReturnOrderStatusesEnum::ACCEPT->value => 'تایید شده',
            ReturnOrderStatusesEnum::DENIED->value => 'تایید نشده',
            ReturnOrderStatusesEnum::SENDING->value => 'ارسال کالای مرجوعی',
            ReturnOrderStatusesEnum::RECEIVED->value => 'دریافت کالای مرجوعی',
            ReturnOrderStatusesEnum::MONEY_RETURNED->value => 'بازگشت مبلغ کالاها',
        ],
    ],

];
