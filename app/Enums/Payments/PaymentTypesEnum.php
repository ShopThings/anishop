<?php

namespace App\Enums\Payments;

enum PaymentTypesEnum: string
{
    case BANK_GATEWAY = 'bank_gateway';
    case IN_PLACE = 'in_place';
    case WALLET = 'wallet';
    case RECEIPT = 'receipt';
}
