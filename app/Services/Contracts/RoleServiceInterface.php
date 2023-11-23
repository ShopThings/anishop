<?php

namespace App\Services\Contracts;

use App\Contracts\VersionInterface;
use Illuminate\Support\Collection;

interface RoleServiceInterface extends VersionInterface
{
    /**
     * @return array
     */
    public function getRoles(): array;

    /**
     * @return Collection
     */
    public function getPermissions(): Collection;
}
