<?php

namespace App\Enums\Payments;

use App\Traits\EnumTranslateTrait;

enum GatewaysEnum: string
{
    use EnumTranslateTrait;

    case LOCAL = 'local';
    case FANAVACARD = 'fanavacard';
    case ATIPAY = 'atipay';
    case ASANPARDAKHT = 'asanpardakht';
    case BEHPARDAKHT = 'behpardakht';
    case DIGIPAY = 'digipay';
    case ETEBARINO = 'etebarino';
    case IDPAY = 'idpay';
    case IRANKISH = 'irankish';
    case NEXTPAY = 'nextpay';
    case OMIDPAY = 'omidpay';
    case PARSIAN = 'parsian';
    case PASARGAD = 'pasargad';
    case PAYIR = 'payir';
    case PAYPAL = 'paypal';
    case PAYPING = 'payping';
    case PAYSTAR = 'paystar';
    case POOLAM = 'poolam';
    case SADAD = 'sadad';
    case SAMAN = 'saman';
    case SEP = 'sep';
    case SEPEHR = 'sepehr';
    case WALLETA = 'walleta';
    case YEKPAY = 'yekpay';
    case ZARINPAL = 'zarinpal';
    case ZIBAL = 'zibal';
    case SEPORDEH = 'sepordeh';
    case RAYANPAY = 'rayanpay';
    case SIZPAY = 'sizpay';
    case VANDAR = 'vandar';
    case AQAYEPARDAKHT = 'aqayepardakht';
    case AZKI = 'azki';
    case PAYFA = 'payfa';

    /**
     * @return string[]
     */
    public static function translationArray(): array
    {
        return [
            self::LOCAL->value => 'لوکال',
            self::FANAVACARD->value => 'فناوا کارت',
            self::ATIPAY->value => 'آتی پی',
            self::ASANPARDAKHT->value => 'آسان پرداخت',
            self::BEHPARDAKHT->value => 'به پرداخت',
            self::DIGIPAY->value => 'دیجی پی',
            self::ETEBARINO->value => 'اعتبارینو',
            self::IDPAY->value => 'آی‌دی پی',
            self::IRANKISH->value => 'ایران کیش',
            self::NEXTPAY->value => 'نکست پی',
            self::OMIDPAY->value => 'امید پی',
            self::PARSIAN->value => 'پارسیان',
            self::PASARGAD->value => 'پاسارگاد',
            self::PAYIR->value => 'پی آی‌آر',
            self::PAYPAL->value => 'پی‌پال',
            self::PAYPING->value => 'پی پینگ',
            self::PAYSTAR->value => 'پی استار',
            self::POOLAM->value => 'پولام',
            self::SADAD->value => 'سداد',
            self::SAMAN->value => 'سامان',
            self::SEP->value => 'سپ',
            self::SEPEHR->value => 'سپهر',
            self::WALLETA->value => 'والتا',
            self::YEKPAY->value => 'یک پی',
            self::ZARINPAL->value => 'زرینپال',
            self::ZIBAL->value => 'زیبال',
            self::SEPORDEH->value => 'سپرده',
            self::RAYANPAY->value => 'رایان پی',
            self::SIZPAY->value => 'سیز پی',
            self::VANDAR->value => 'وندر',
            self::AQAYEPARDAKHT->value => 'آقای پرداخت',
            self::AZKI->value => 'از کی',
            self::PAYFA->value => 'پی‌فا',
        ];
    }
}
