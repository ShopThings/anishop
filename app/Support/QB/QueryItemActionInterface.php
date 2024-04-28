<?php

namespace App\Support\QB;

interface QueryItemActionInterface
{
    /**
     * @param ...$parameters
     * @return mixed
     */
    public function action(...$parameters);
}
