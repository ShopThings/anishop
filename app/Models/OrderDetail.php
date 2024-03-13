<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'description' => CleanHtmlCast::class,
        'is_needed_factor' => 'boolean',
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
     * Determine if current order has paid any(even partially).
     *
     * @return bool
     */
    public function hasAnyPaid(): bool
    {
        return $this->orders()
                ->where('payment_status', PaymentStatusesEnum::SUCCESS)
                ->count() > 0;
    }

    /**
     * Determine if current order has paid completely.
     *
     * @return bool
     */
    public function hasCompletePaid(): bool
    {
        $successOrdersCount = $this->orders()
            ->where('payment_status', PaymentStatusesEnum::SUCCESS)
            ->count();
        $allOrdersCount = $this->orders()->count();

        return $successOrdersCount === $allOrdersCount;
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeWithAnyPaidOrder(Builder $query): mixed
    {
        return $query->whereHas('orders', function ($query) {
            $query->where('payment_status', PaymentStatusesEnum::SUCCESS);
        });
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeWithCompletePaidOrder(Builder $query): mixed
    {
        return $query->whereHas('orders', function ($query) {
            $query->where('payment_status', PaymentStatusesEnum::SUCCESS);
        }, '=', function ($query) {
            // The count of all related payments
            $query->count();
        });
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
