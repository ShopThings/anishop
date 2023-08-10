<?php

namespace App\Contracts\Event;

interface EventHandlerInterface
{
    /**
     * Handle incoming event
     *
     * @param EventInterface $event
     * @return void
     */
    public function handle(EventInterface $event): void;
}
