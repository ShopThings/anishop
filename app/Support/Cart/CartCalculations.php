<?php

namespace App\Support\Cart;

class CartCalculations
{
    /**
     * @param Cart $cart
     */
    public function __construct(protected Cart $cart)
    {
    }

    /**
     * @return int
     */
    public function count(): int
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($count, $item) {
            return $count + $item->qty;
        }, 0);
    }

    /**
     * @return int
     */
    public function shipmentCount(): int
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        $totalShipment = 0;
        $hasAnySimpleProduct = false;
        $this->cart->getContent()->each(function ($item) use (&$totalShipment, &$hasAnySimpleProduct) {
            $quantity = $item->qty;

            if ($item?->has_separate_shipment) {
                $totalShipment += $quantity;
            } else {
                $hasAnySimpleProduct = true;
            }
        });

        if ($hasAnySimpleProduct) $totalShipment += 1;

        return $totalShipment;
    }

    /**
     * @return int
     */
    public function totalWeight(): int
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $weight = $item->weight;

            return $total + ($quantity * $weight);
        }, 0);
    }

    /**
     * @return float
     */
    public function totalPrice(): float
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $price = $item->actual_price;
            $tax = $item->tax_rate;

            return $total + ($quantity * ($price + ($price * $tax / 100)));
        }, 0);
    }

    /**
     * @return float
     */
    public function totalDiscountedPrice(): float
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $price = $item->price;
            $tax = $item->tax_rate;

            return $total + ($quantity * ($price + ($price * $tax / 100)));
        }, 0);
    }

    /**
     * @return float
     */
    public function subtotalPrice(): float
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $price = $item->actual_price;

            return $total + ($quantity * $price);
        }, 0);
    }

    /**
     * @return float
     */
    public function subtotalDiscountedPrice(): float
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $price = $item->price;

            return $total + ($quantity * $price);
        }, 0);
    }

    /**
     * @return float
     */
    public function totalTaxPrice(): float
    {
        if ($this->cart->getContent()->isEmpty()) return 0;

        return $this->cart->getContent()->reduce(function ($total, $item) {
            $quantity = $item->qty;
            $price = $item->price;
            $tax = $item->tax_rate;

            return $total + ($quantity * ($price * $tax / 100));
        }, 0);
    }
}
