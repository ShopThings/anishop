<?php

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;

return [

    'gateway' => [
        'success' => 'Successful transaction.',
        'error' => 'Transaction Failed!',
        'invalid' => 'Invalid Transaction Parameters!',
    ],

    'status' => [
        PaymentStatusesEnum::SUCCESS->value => 'Payed',
        PaymentStatusesEnum::FAILED->value => 'Failed Payment',
        PaymentStatusesEnum::WAIT_VERIFY->value => 'Wait To Verify',
        PaymentStatusesEnum::WAIT->value => 'Wait For Payment',
        PaymentStatusesEnum::NOT_PAYED->value => 'Not Payed',
    ],

    'gateways' => [
        GatewaysEnum::BEHPARDAKHT->value => 'BehPardakht Gateway',
        GatewaysEnum::IDPAY->value => 'IDPay Gateway',
        GatewaysEnum::IRANKISH->value => 'IranKish Gateway',
        GatewaysEnum::SEPEHR->value => 'Sepehr Gateway (MABNA)',
        GatewaysEnum::SADAD->value => 'Sadad Gateway',
        GatewaysEnum::PARSIAN->value => 'Parsian Gateway (TAP)',
        GatewaysEnum::ZARINPAL->value => 'ZarinPal Gateway',
    ],

];
