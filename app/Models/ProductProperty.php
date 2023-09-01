<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductProperty extends Model implements Buyable
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_special' => 'boolean',
        'is_available' => 'boolean',
        'show_coming_soon' => 'boolean',
        'show_call_for_more' => 'boolean',
        'is_published' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->product()->title;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }
}
