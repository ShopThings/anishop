<?php

namespace App\Support\QB\ItemActions;

use App\Support\QB\BaseQueryItemAction;
use Closure;

class IsMultipleItemAction extends BaseQueryItemAction
{
    public function __construct(Closure $callback)
    {
        parent::__construct('is_multiple');
        $this->setCallback($callback);
    }
}
