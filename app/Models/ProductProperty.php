<?php

namespace App\Models;

use App\Support\Cart\BuyableInterface as Buyable;
use App\Support\Model\ExtendedModel as Model;
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
        'updated_at' => 'datetime',
    ];

    public static function nanoIdColumn(): string
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
            !$this->show_coming_soon &&
            !$this->show_call_for_more &&
            $this->is_available &&
            $this->is_published &&
            $this->stock_count > 0;
    }

    /**
     * @return bool
     */
    public function hasFestivalDiscount(): bool
    {
        return $this->product()->whereHas('festivals.festival', function (Builder $query) {
            $query->published()->activated();
        })->exists();
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
        $price = $this->price;

        // check product if it's in festival or not and if so, apply festival discount percentage to it
        $festivalProduct = $this->product()->withWhereHas('festivals.festival', function ($query) {
            $query->published()->activated();
        })->first();

        $record = $festivalProduct?->festivals?->first();

        if (!is_null($record)) {
            $off = $this->price * $record->discount_percentage / 100.00;
            $price = $this->price - $off;
        } else {
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
        }

        return $price;
    }
}
