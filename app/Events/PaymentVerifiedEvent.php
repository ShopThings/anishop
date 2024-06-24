<?php

namespace App\Events;

use App\Models\OrderDetail;
use App\Models\User;
use App\Support\Event\AbstractEvent;

class PaymentVerifiedEvent extends AbstractEvent
{
    public function __construct(
        public User        $user,
        public OrderDetail $order
    )
    {
    }
}
