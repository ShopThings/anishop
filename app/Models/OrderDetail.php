<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderDetail extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_in_place_delivery' => 'boolean',
        'is_product_returned_to_stock' => 'boolean',
        'ordered_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function sendStatusChanger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_status_changed_by');
    }

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'orders',
            'order_key_id',
            'key_id'
        );
    }

    /**
     * @return HasOne
     */
    public function returnOrder(): HasOne
    {
        return $this->hasOne(ReturnOrderRequest::class, 'order_detail_id');
    }
}
