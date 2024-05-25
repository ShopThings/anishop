<?php

namespace App\Services\Contracts;

use App\Contracts\VersionInterface;
use App\Models\User;
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

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserPermissions(User $user): Collection;
}
