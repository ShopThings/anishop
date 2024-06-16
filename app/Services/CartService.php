<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\Contracts\CartServiceInterface;
use App\Traits\VersionTrait;
use Illuminate\Support\Collection;

class CartService implements CartServiceInterface
{
    use VersionTrait;

    public function __construct(
        protected CartRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getUserCarts(User $user): Collection
    {
        return $this->repository->getUserCarts($user);
    }

    /**
     * @inheritDoc
     */
    public function getUserDefaultCart(User $user): Collection
    {
        return $this->repository->getUserDefaultCart($user);
    }

    /**
     * @inheritDoc
     */
    public function getUserWishlistCart(User $user): Collection
    {
        return $this->repository->getUserWishlistCart($user);
    }

    /**
     * @inheritDoc
     */
    public function saveDefaultCart(User $user, array $items): bool
    {
        return $this->repository->saveDefaultCart($user, $items);
    }

    /**
     * @inheritDoc
     */
    public function saveWishlistCart(User $user, array $items): bool
    {
        return $this->repository->saveWishlistCart($user, $items);
    }

    /**
     * @inheritDoc
     */
    public function deleteAllCarts(User $user): bool
    {
        return $this->repository->deleteAllCarts($user);
    }

    /**
     * @inheritDoc
     */
    public function deleteCart(string $cartName, User $user): bool
    {
        return $this->repository->deleteCart($cartName, $user);
    }
}
