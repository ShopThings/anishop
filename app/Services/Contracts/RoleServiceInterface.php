<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;

interface RoleServiceInterface extends ServiceInterface
{
    /**
     * @return array
     */
    public function getRoles(): array;
}
