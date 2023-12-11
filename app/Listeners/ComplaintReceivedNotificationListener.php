<?php

namespace App\Listeners;

use App\Events\ComplaintAddedEvent;
use App\Support\Event\AbstractListener;

class ComplaintReceivedNotificationListener extends AbstractListener
{
    public $tries = 5;

    /**
     * Handle the event.
     */
    public function handle(ComplaintAddedEvent $event): void
    {
        //
    }
}
