<?php

namespace App\Support\Cart;

interface BuyableInterface
{
    /**
     * @return int|string
     */
    public function getBuyableIdentifier(): int|string;

    /**
     * @return string
     */
    public function getBuyableDescription(): string;

    /**
     * @return float
     */
    public function getBuyablePrice(): float;
}
