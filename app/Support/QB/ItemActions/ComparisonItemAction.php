<?php

namespace App\Support\QB\ItemActions;

use App\Support\QB\BaseQueryItemAction;
use Closure;

class ComparisonItemAction extends BaseQueryItemAction
{
    public function __construct(Closure $callback)
    {
        parent::__construct('comparison');
        $this->setCallback($callback);
    }
}
