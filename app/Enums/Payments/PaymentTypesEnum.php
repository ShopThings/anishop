<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;

enum PaymentTypesEnum: string
{
    use EnumTranslateTrait;

    case BANK_GATEWAY = 'bank_gateway';
    case IN_PLACE = 'in_place';
    case WALLET = 'wallet';
    case RECEIPT = 'receipt';

    /**
     * @return string[]
     */
    protected static function translationArray(): array
    {
        return [
            self::BANK_GATEWAY->value => 'درگاه بانک',
            self::IN_PLACE->value => 'پرداخت درب منزل',
            self::WALLET->value => 'کیف پول',
            self::RECEIPT->value => 'فیش واریز',
        ];
    }
}
