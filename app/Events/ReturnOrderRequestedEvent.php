<?php

namespace App\Events;

use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Support\Event\AbstractEvent;

class ReturnOrderRequestedEvent extends AbstractEvent
{
    public function __construct(
        public User               $user,
        public string             $returnCode,
        public string             $orderCode,
        public ReturnOrderRequest $request
    )
    {
    }
}
