<?php

namespace App\Support\Cart\Filters;

use App\Support\Cart\Cart;
use Illuminate\Database\Eloquent\Model;

interface CartFilterInterface
{
    /**
     * @param Model $item
     * @param int $quantity
     * @return bool
     */
    public function validate(Model $item, int $quantity, Cart $cart): bool;
}
