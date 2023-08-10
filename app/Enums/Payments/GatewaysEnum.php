<?php

namespace App\Enums\Payments;

enum GatewaysEnum: string
{
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
}
