<?php

namespace App\Repositories;

use App\Models\ShoppingCart;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Support\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CartRepository extends Repository implements CartRepositoryInterface
{
    public function __construct(ShoppingCart $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getUserCarts(User $user): Collection
    {
        $cartNameDefault = config('market.cart_name.default');
        $cartNameWishlist = config('market.cart_name.wishlist');

        if (is_null($cartNameDefault) && is_null($cartNameWishlist)) {
            return new Collection();
        }

        return $this->model->newQuery()
            ->where('identifier', $user->username)
            ->whereIn('instance', [$cartNameDefault, $cartNameWishlist])
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getUserDefaultCart(User $user): Collection
    {
        $cartName = config('market.cart_name.default');

        if (is_null($cartName)) return collect();
        return $this->getCart($cartName, $user);
    }

    /**
     * @inheritDoc
     */
    public function getUserWishlistCart(User $user): Collection
    {
        $cartName = config('market.cart_name.wishlist');

        if (is_null($cartName)) return collect();
        return $this->getCart($cartName, $user);
    }

    /**
     * @inheritDoc
     */
    public function saveDefaultCart(User $user, array $items): bool
    {
        $cartName = config('market.cart_name.default');

        if (is_null($cartName)) return false;
        return $this->saveToCart($cartName, $user, $items) instanceof Model;
    }

    /**
     * @inheritDoc
     */
    public function saveWishlistCart(User $user, array $items): bool
    {
        $cartName = config('market.cart_name.wishlist');

        if (is_null($cartName)) return false;
        return $this->saveToCart($cartName, $user, $items) instanceof Model;
    }

    /**
     * @inheritDoc
     */
    public function deleteAllCarts(User $user): bool
    {
        return !!$this->model->newQuery()
            ->where('identifier', $user->username)
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function deleteCart(string $cartName, User $user): bool
    {
        return !!$this->model->newQuery()
            ->where('identifier', $user->username)
            ->where('instance', $cartName)
            ->delete();
    }

    /**
     * @param string $cartName
     * @param User $user
     * @return Collection
     */
    protected function getCart(string $cartName, User $user): Collection
    {
        $cart = $this->model->newQuery()
            ->where('identifier', $user->username)
            ->where('instance', $cartName)
            ->first();

        if (is_null($cart)) return new Collection();
        return new Collection($cart->content);
    }

    /**
     * @param string $cartName
     * @param User $user
     * @param array $items
     * @return Model|null
     */
    protected function saveToCart(string $cartName, User $user, array $items): ?Model
    {
        try {
            return $this->model::updateOrCreate([
                'identifier' => $user->username,
                'instance' => $cartName,
            ], [
                'content' => $items,
            ]);
        } catch (\Exception) {
            return null;
        }
    }
}
