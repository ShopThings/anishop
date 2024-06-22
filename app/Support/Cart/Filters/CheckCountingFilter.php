<?php

namespace App\Support\Cart\Filters;

use App\Support\Cart\Cart;
use Illuminate\Database\Eloquent\Model;

class CheckCountingFilter implements CartFilterInterface
{
    /**
     * @inheritDoc
     */
    public function validate(Model $item, int $quantity, Cart $cart): bool
    {
        // check quantity
        if ($quantity < 1) {
            $quantity = 1;
        }

        $prevQty = 0;
        $prevItem = null;
        foreach ($cart->getContent() as $currentItem) {
            if ($currentItem->code === $item->code) {
                $prevItem = $currentItem;
                break;
            }
        }
        // get previous item's quantity
        if (!is_null($prevItem)) {
            $prevQty = $prevItem->qty;
        }

        // check if provided quantity (with previous one too), has any problem or not
        if (($quantity + $prevQty) > $item->max_cart_count || ($quantity + $prevQty) > $item->stock_count) {
            return false;
        }
        return true;
    }
}
