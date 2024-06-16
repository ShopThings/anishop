<?php

namespace App\Events;

use App\Models\User;
use App\Support\Event\AbstractEvent;

class ContactAddedEvent extends AbstractEvent
{
    public function __construct(
        public User $user
    )
    {
    }
}
