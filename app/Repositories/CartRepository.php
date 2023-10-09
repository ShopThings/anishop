<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getUserCarts(User $user): Collection
    {
        return Cart::inatance('temp')->restore($user->username)->content();
    }
}
