<?php

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;

return [

    'gateway' => [
        'success' => 'تراکنش با موفقیت انجام شد.',
        'error' => 'تراکنش ناموفق بود، در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برگشت داده می‌شود.',
        'invalid' => 'پارامترهای ارسالی از درگاه نامعتبر است.',
    ],

    'status' => [
        PaymentStatusesEnum::SUCCESS->value => 'پرداخت شده',
        PaymentStatusesEnum::FAILED->value => 'پرداخت ناموفق',
        PaymentStatusesEnum::WAIT_VERIFY->value => 'در انتظار تایید',
        PaymentStatusesEnum::WAIT->value => 'در انتظار پرداخت',
        PaymentStatusesEnum::NOT_PAYED->value => 'پرداخت نشده',
    ],

    'gateways' => [
        GatewaysEnum::BEHPARDAKHT->value => 'درگاه بانک - به پرداخت',
        GatewaysEnum::IDPAY->value => 'درگاه بانک - آیدی پی',
        GatewaysEnum::IRANKISH->value => 'درگاه بانک - ایران کیش',
        GatewaysEnum::SEPEHR->value => 'درگاه بانک - پرداخت الکترونیک سپهر (مبنا)',
        GatewaysEnum::SADAD->value => 'درگاه بانک - سداد',
        GatewaysEnum::PARSIAN->value => 'درگاه بانک - تجارت الکترونیک پارسیان (تاپ)',
        GatewaysEnum::ZARINPAL->value => 'درگاه بانک - زرین پال',
    ],

];
