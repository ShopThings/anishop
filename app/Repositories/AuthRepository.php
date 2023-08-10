<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function resetPassword(User $user, $newPassword): bool
    {
        // TODO: Implement resetPassword() method.
        return false;
    }
}
