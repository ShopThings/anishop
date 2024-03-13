<?php

namespace App\Support\Event;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class AbstractListener implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    use InteractsWithQueue;
}
