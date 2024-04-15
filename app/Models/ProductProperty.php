<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Cart\BuyableInterface as Buyable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Parables\NanoId\GeneratesNanoId;

class ProductProperty extends Model implements Buyable
{
    use GeneratesNanoId;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'discounted_from' => 'datetime',
        'discounted_until' => 'datetime',
        'is_special' => 'boolean',
        'is_available' => 'boolean',
        'show_coming_soon' => 'boolean',
        'show_call_for_more' => 'boolean',
        'is_published' => 'boolean',
        'has_separate_shipment' => 'boolean',
    ];

    public function nanoIdColumn()
    {
        return 'code';
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return bool
     */
    public function isAvailableForCart(): bool
    {
        return $this->product->is_available &&
            $this->product->is_published &&
            $this->is_available &&
            $this->is_published &&
            $this->stock_count > 0;
    }

    /**
     * @inheritDoc
     */
    public function getBuyableIdentifier(): int|string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getBuyableDescription(): string
    {
        return $this->product->title;
    }

    /**
     * @inheritDoc
     */
    public function getBuyablePrice(): float
    {
        return $this->_getDiscountedPrice();
    }

    /**
     * @return float
     */
    private function _getDiscountedPrice(): float
    {
        $id = $this->id;
        $price = $this->price;

        // check if product have discount
        if (
            (
                !isset($this->discounted_from) &&
                isset($this->discounted_until) &&
                $this->discounted_until >= now()
            ) ||
            (
                isset($this->discounted_from) &&
                !isset($this->discounted_until) &&
                $this->discounted_from <= now()
            ) ||
            (
                isset($this->discounted_from, $this->discounted_until) &&
                $this->discounted_from <= now() && $this->discounted_until >= now()
            )
        ) {
            $price = $this->discounted_price;
        }

        // check product if it's in festival or not and if so, apply festival discount percentage to it
        $festivalProduct = $this->product()->with('festivals.items', function (Builder $query) use ($id) {
            $query->where('product_id', $id);
        })->published()->activated()->first(['discount_percentage']);

        if ($festivalProduct) {
            $off = floor($this->price * $festivalProduct->discount_percentage / 100.00);
            $price = $this->price - $off;
        }

        return $price;
    }
}
