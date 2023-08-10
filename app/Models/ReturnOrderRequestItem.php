<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnOrderRequestItem extends Model
{
    use HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function returnOrder(): BelongsTo
    {
        return $this->belongsTo(ReturnOrderRequest::class, 'return_code', 'code');
    }

    /**
     * @return BelongsTo
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
