<?php

namespace App\Enums\Payments;

enum PaymentStatusesEnum: int
{
    case SUCCESS = 1;
    case FAILED = 0;
    case WAIT_VERIFY = -7;
    case WAIT = -8;
    case NOT_PAYED = -9;
}
