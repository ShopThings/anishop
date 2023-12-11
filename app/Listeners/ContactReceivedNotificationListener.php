<?php

namespace App\Listeners;

use App\Events\ContactAddedEvent;
use App\Support\Event\AbstractListener;

class ContactReceivedNotificationListener extends AbstractListener
{
    public $tries = 2;

    /**
     * Handle the event.
     */
    public function handle(ContactAddedEvent $event): void
    {
        //
    }
}
