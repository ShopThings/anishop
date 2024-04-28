<?php

namespace App\Support\QB\ItemActions;

use App\Support\QB\BaseQueryItemAction;
use Closure;

class HasReplacementItemAction extends BaseQueryItemAction
{
    public function __construct(Closure $callback)
    {
        parent::__construct('has_replacement');
        $this->setCallback($callback);
    }
}
