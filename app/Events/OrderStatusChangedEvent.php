<?php

namespace App\Events;

use App\Models\User;
use App\Support\Event\AbstractEvent;

class OrderStatusChangedEvent extends AbstractEvent
{
    public function __construct(
        public User   $user,
        public string $orderCode,
        public string $sendStatus
    )
    {
    }
}
