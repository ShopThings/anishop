<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * @param User $user
     * @param $newPassword
     * @return bool
     */
    public function resetPassword(User $user, $newPassword): bool;
}
