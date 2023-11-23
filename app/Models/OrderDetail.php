<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Mews\Purifier\Casts\CleanHtml;
use Parables\NanoId\GeneratesNanoId;

class OrderDetail extends Model
{
    use GeneratesNanoId;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'description' => CleanHtml::class,
        'is_needed_factor' => 'boolean',
        'is_in_place_delivery' => 'boolean',
        'is_product_returned_to_stock' => 'boolean',
        'ordered_at' => 'datetime',
    ];

    public function nanoIdColumn(): string
    {
        return 'code';
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * @return BelongsTo
     */
    public function sendStatusChanger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_status_changed_by');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'key_id');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_key_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function returnOrder(): HasOne
    {
        return $this->hasOne(ReturnOrderRequest::class, 'order_detail_id');
    }
}
