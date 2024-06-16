<?php

namespace App\Support\QB\ItemActions;

use App\Support\QB\BaseQueryItemAction;
use Closure;

class BetweenItemAction extends BaseQueryItemAction
{
    public function __construct(Closure $callback)
    {
        parent::__construct('between');
        $this->setCallback($callback);
    }
}
