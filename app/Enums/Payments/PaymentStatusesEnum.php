<?php

namespace App\Enums\Payments;

enum PaymentStatusesEnum: int
{
    case SUCCESS = 1;
    case FAILED = 0;
    case WAIT_VERIFY = -7;
    case WAIT = -8;
    case NOT_PAYED = -9;

    /**
     * @return string[]
     */
    private static function translationArray(): array
    {
        return [
            self::SUCCESS->value => 'پرداخت موفق',
            self::FAILED->value => 'پرداخت ناموفق',
            self::WAIT_VERIFY->value => 'در انتظار تایید',
            self::WAIT->value => 'در انتظار پرداخت',
            self::NOT_PAYED->value => 'پرداخت نشده',
        ];
    }

    /**
     * @param array|string $statuses
     * @return array|string|null
     */
    public static function getTranslations(array|string $statuses): array|string|null
    {
        $translates = self::translationArray();
        if (is_array($statuses)) {
            $newArr = [];
            foreach ($statuses as $status) {
                $newArr[$status] = $translates[$status] ?? $status;
            }
            return count($newArr) ? $newArr : null;
        }
        return $translates[$statuses] ?? $statuses;
    }
}
