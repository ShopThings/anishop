<?php

namespace App\Models;

use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Support\WhereBuilder\GetterExpressionInterface;
use App\Traits\HasDeletedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mews\Purifier\Casts\CleanHtml;
use Parables\NanoId\GeneratesNanoId;

class ReturnOrderRequest extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        GeneratesNanoId;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
        'requested_at',
    ];

    protected $casts = [
        'not_accepted_description' => CleanHtml::class,
        'status' => ReturnOrderStatusesEnum::class,
        'seen_status' => 'boolean',
        'status_changed_at' => 'datetime',
        'responded_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

    public function nanoIdColumn()
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    /**
     * @return HasMany
     */
    public function returnOrderItems(): HasMany
    {
        return $this->hasMany(ReturnOrderRequestItem::class, 'return_code', 'code');
    }

    /**
     * @param GetterExpressionInterface $where
     * @return bool
     */
    public function hasReturnOrderItemWhere(GetterExpressionInterface $where): bool
    {
        return $this->returnOrderItems()->whereRaw($where->getStatement(), $where->getBindings())->count();
    }

    /**
     * @return BelongsTo
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }
}
