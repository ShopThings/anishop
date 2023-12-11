<?php

namespace App\Support\Event;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;
}
