<?php

namespace App\Support\QB\ItemActions;

use App\Support\QB\BaseQueryItemAction;
use Closure;

class NullableItemAction extends BaseQueryItemAction
{
    public function __construct(Closure $callback)
    {
        parent::__construct('nullable');
        $this->setCallback($callback);
    }
}
