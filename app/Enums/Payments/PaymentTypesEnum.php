<?php

namespace App\Enums\Payments;

enum PaymentTypesEnum: string
{
    case BANK_GATEWAY = 'bank_gateway';
    case IN_PLACE = 'in_place';
    case WALLET = 'wallet';
    case RECEIPT = 'receipt';

    /**
     * @return string[]
     */
    private static function translationArray(): array
    {
        return [
            self::BANK_GATEWAY->value => 'درگاه بانک',
            self::IN_PLACE->value => 'پرداخت درب منزل',
            self::WALLET->value => 'کیف پول',
            self::RECEIPT->value => 'فیش واریز',
        ];
    }

    /**
     * @param array|string $types
     * @return array|string|null
     */
    public static function getTranslations(array|string $types): array|string|null
    {
        $translates = self::translationArray();
        if (is_array($types)) {
            $newArr = [];
            foreach ($types as $type) {
                $newArr[$type] = $translates[$type] ?? $type;
            }
            return count($newArr) ? $newArr : null;
        }
        return $translates[$types] ?? $types;
    }
}
