<?php

namespace App\Enums\Results;

enum ReturnOrderToStockResult
{
    case ALREADY_RETURNED;
    case SUCCESS;
    case ERROR;
}
