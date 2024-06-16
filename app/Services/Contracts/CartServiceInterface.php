<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface CartServiceInterface
{
    /**
     * @param User $user
     * @return Collection
     */
    public function getUserCarts(User $user): Collection;

    /**
     * Unfortunately user's cart will replace with your default cart temporary (just don't save it to your cart).
     *
     * @param User $user
     * @return Collection
     */
    public function getUserDefaultCart(User $user): Collection;

    /**
     * Unfortunately user's cart will replace with your wishlist cart temporary (just don't save it to your cart).
     *
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
