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

/**
 * @method Builder withCompletePaidOrder(string $condition = 'and')
 * @method Builder withoutAnyPaidOrder(string $condition = 'and')
 * @method Builder withAnyPaidOrder(string $condition = 'and')
 * @method Builder withWaitingOrder(string $condition = 'and')
 */
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

    public static function nanoIdColumn(): string
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
    public function reservedOrders(): HasMany
    {
        return $this->hasMany(OrderReserve::class, 'order_key_id');
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
     * @param string $condition
     * @return mixed
     */
    public function scopeWithCompletePaidOrder(Builder $query, string $condition = 'and'): mixed
    {
        $method = 'whereHas';
        if ($condition == 'or') {
            $method = 'orWhereHas';
        }

        return $query->{$method}('orders', function ($query) {
            $query->where('payment_status', PaymentStatusesEnum::SUCCESS);
        }, '=', $this->orders()->count());
    }

    /**
     * @param Builder $query
     * @param string $condition
     * @return mixed
     */
    public function scopeWithoutAnyPaidOrder(Builder $query, string $condition = 'and'): mixed
    {
        $method = 'whereHas';
        if ($condition == 'or') {
            $method = 'orWhereHas';
        }

        return $query->{$method}('orders', function ($query) {
            $query->where(function ($q) {
                $q
                    ->where('payment_status', PaymentStatusesEnum::SUCCESS)
                    ->orWhere('payment_status', PaymentStatusesEnum::PARTIAL_SUCCESS);
            });
        }, '=', 0);
    }

    /**
     * @param Builder $query
     * @param string $condition
     * @return mixed
     */
    public function scopeWithAnyPaidOrder(Builder $query, string $condition = 'and'): mixed
    {
        $method = 'whereHas';
        if ($condition == 'or') {
            $method = 'orWhereHas';
        }

        return $query->{$method}('orders', function ($query) {
            $query->where('payment_status', PaymentStatusesEnum::SUCCESS);
        });
    }

    /**
     * @param Builder $query
     * @param string $condition
     * @return mixed
     */
    public function scopeWithWaitingOrder(Builder $query, string $condition = 'and'): mixed
    {
        $method = 'whereHas';
        if ($condition == 'or') {
            $method = 'orWhereHas';
        }

        return $query->{$method}('orders', function ($query) {
            $query->where('payment_status', PaymentStatusesEnum::WAIT);
        });
    }
}
