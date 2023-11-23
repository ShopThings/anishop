<?php

use App\Enums\Orders\ReturnOrderStatusesEnum;

return [

    'return' => [
        'status' => [
            ReturnOrderStatusesEnum::CHECKING->value => 'Checking',
            ReturnOrderStatusesEnum::DENIED_BY_USER->value => 'Denied By User',
            ReturnOrderStatusesEnum::ACCEPT->value => 'Accepted',
            ReturnOrderStatusesEnum::DENIED->value => 'Not Accepted',
            ReturnOrderStatusesEnum::SENDING->value => 'Sending Product',
            ReturnOrderStatusesEnum::RECEIVED->value => 'Received Product',
            ReturnOrderStatusesEnum::RETURN_TO_USER->value => 'Return Products Back To User',
            ReturnOrderStatusesEnum::RECEIVED_BY_USER->value => 'Received Products Back By User',
            ReturnOrderStatusesEnum::MONEY_RETURNED->value => 'Money Has Been Returned',
        ],
    ],

];
