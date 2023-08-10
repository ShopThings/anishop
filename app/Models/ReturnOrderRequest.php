<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasDeletedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnOrderRequest extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
        'requested_at',
    ];

    protected $casts = [
        'seen_status' => 'boolean',
        'status_changed_at' => 'datetime',
        'responded_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

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
}
