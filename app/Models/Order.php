<?php

namespace App\Models;

use App\Enums\DatabaseEnum;
use App\Enums\Payments\GatewaysEnum;
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
        'payment_method_gateway_type' => GatewaysEnum::class,
        'payment_status' => PaymentStatusesEnum::class,
        'payment_status_changed_at' => 'datetime',
        'paid_at' => 'datetime',
        'can_pay_again_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'code';
    }

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
        return $this->hasOne(OrderDetail::class, 'id', 'key_id');
    }

    /**
     * @return HasOne
     */
    public function paymentMethod(): HasOne
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(GatewayPayment::class, 'order_id');
    }

    /**
     * @return bool
     */
    public function waitForPay(): bool
    {
        return $this->payment_status === PaymentStatusesEnum::PENDING->value;
    }

    /**
     * Determine if current order has paid.
     *
     * @return bool
     */
    public function hasPaid(): bool
    {
        return $this->payments()->where('status', DatabaseEnum::DB_YES)->count() > 0;
    }
}
