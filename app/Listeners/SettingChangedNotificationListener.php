<?php

namespace App\Listeners;

use App\Events\SettingUpdatedEvent;
use App\Support\Event\AbstractListener;

class SettingChangedNotificationListener extends AbstractListener
{
    public $tries = 2;

    /**
     * Handle the event.
     */
    public function handle(SettingUpdatedEvent $event): void
    {
        //
    }
}
