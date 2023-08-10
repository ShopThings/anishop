<?php

namespace App\Models;

use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasCreatedRelationTrait;

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'payment_method_type' => PaymentTypesEnum::class,
        'payment_status' => PaymentStatusesEnum::class,
        'payment_status_changed_at' => 'datetime',
        'payed_at' => 'datetime',
        'must_delete_later' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function paymentStatusChanger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_status_changed_by');
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(OrderDetail::class, 'order_key_id', 'key_id');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_key_id', 'key_id');
    }

    /**
     * @return HasMany
     */
    public function reservedOrders(): HasMany
    {
        return $this->hasMany(OrderReserve::class, 'order_code', 'code');
    }
}
