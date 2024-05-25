<?php

namespace App\Support\QB;

use Closure;

abstract class BaseQueryItemAction implements QueryItemActionInterface
{
    /**
     * @var Closure
     */
    protected Closure $callback;

    public function __construct(public string $name)
    {
    }

    /**
     * @param Closure $callback
     * @return static
     */
    public function setCallback(Closure $callback): static
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function action(...$parameters)
    {
        $callback = $this->callback;
        $callback(...$parameters);
    }
}
