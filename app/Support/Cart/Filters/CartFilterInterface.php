<?php

namespace App\Support\Cart\Filters;

use App\Support\Cart\Cart;
use Illuminate\Database\Eloquent\Model;

interface CartFilterInterface
{
    /**
     * @param Model $item
     * @param int $quantity
     * @param Cart $cart
     * @return bool
     */
    public function validate(Model $item, int $quantity, Cart $cart): bool;
}
