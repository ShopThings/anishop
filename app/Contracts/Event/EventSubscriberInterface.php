<?php

namespace App\Contracts\Event;

interface EventSubscriberInterface
{
    /**
     * @param $events
     * @return mixed
     */
    public function subscribe($events): mixed;
}
