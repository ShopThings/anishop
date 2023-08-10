<?php

namespace App\Contracts\Event;

interface EventInterface
{
    /**
     * Get name of the event
     *
     * @return string
     */
    public static function getName(): string;
}
