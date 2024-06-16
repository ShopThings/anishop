<?php

namespace App\Events;

use App\Models\OrderDetail;
use App\Support\Event\AbstractEvent;

class OrderPaidEvent extends AbstractEvent
{
    public function __construct(
        public OrderDetail $order
    )
    {
    }
}
