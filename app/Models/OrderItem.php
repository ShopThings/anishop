<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_returned' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_key_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return HasMany
     */
    public function returnOrderItems(): HasMany
    {
        return $this->HasMany(ReturnOrderRequestItem::class, 'order_item_id');
    }
}
