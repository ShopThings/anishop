<?php

namespace App\Repositories\Contracts;

use App\Contracts\RepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

interface CartRepositoryInterface extends RepositoryInterface
{
    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCarts(User $user): Collection;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserDefaultCart(User $user): Collection;

    /**
     * @param User $user
     * @return Collection
     */
    public function getUserWishlistCart(User $user): Collection;

    /**
     * @param User $user
     * @param array $items
     * @return bool
     */
    public function saveDefaultCart(User $user, array $items): bool;

    /**
     * @param User $user
     * @param array $items
     * @return bool
     */
    public function saveWishlistCart(User $user, array $items): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function deleteAllCarts(User $user): bool;

    /**
     * @param string $cartName
     * @param User $user
     * @return bool
     */
    public function deleteCart(string $cartName, User $user): bool;
}
