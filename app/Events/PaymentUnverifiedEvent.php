<?php

namespace App\Events;

use App\Models\OrderDetail;
use App\Models\User;
use App\Support\Event\AbstractEvent;

class PaymentUnverifiedEvent extends AbstractEvent
{
    public function __construct(
        public User        $user,
        public OrderDetail $order,
        public string      $message
    )
    {
    }
}
