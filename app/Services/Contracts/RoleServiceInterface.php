<?php

namespace App\Services\Contracts;

use App\Contracts\VersionInterface;

interface RoleServiceInterface extends VersionInterface
{
    /**
     * @return array
     */
    public function getRoles(): array;
}
