<?php

namespace App\Models;

use App\Enums\Payments\GatewaysEnum;
use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayPayment extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'gateway_type' => GatewaysEnum::class,
        'meta' => 'array',
        'payed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
