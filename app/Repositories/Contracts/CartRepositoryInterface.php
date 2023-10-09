<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCarts(User $user): Collection;
}
