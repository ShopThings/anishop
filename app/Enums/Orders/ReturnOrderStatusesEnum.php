<?php

namespace App\Enums\Orders;

enum ReturnOrderStatusesEnum: string
{
    case CHECKING = 'checking';
    case DENIED_BY_USER = 'denied_by_user';
    case ACCEPT = 'accept';
    case DENIED = 'denied';
    case SENDING = 'sending';
    case RECEIVED = 'received';
    case MONEY_RETURNED = 'money_returned';

    /**
     * @return string[]
     */
    private static function translationArray(): array
    {
        return [
            self::CHECKING->value => 'در حال بررسی',
            self::DENIED_BY_USER->value => 'لغو توسط کاربر',
            self::ACCEPT->value => 'قبول درخواست',
            self::DENIED->value => 'رد درخواست',
            self::SENDING->value => 'ارسال مرسولات توسط کاربر',
            self::RECEIVED->value => 'دریافت مرسولات توسط پذیرنده',
            self::MONEY_RETURNED->value => 'بازگشت وجه پرداخت شده به کاربر',
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
