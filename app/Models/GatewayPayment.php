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
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Set payment's paid at
     *
     * @return self
     */
    public function setAsPaid(): self
    {
        return tap($this->forceFill(['paid_at' => now()]))->save();
    }

    /**
     * Set payment's status
     *
     * @param bool $status
     * @return self
     */
    public function setStatus(bool $status): self
    {
        return tap($this->forceFill(['status' => $status]))->save();
    }

    /**
     * Set payment's transaction
     *
     * @param string $transactionId
     * @return self
     */
    public function setTransaction(string $transactionId): self
    {
        return tap($this->forceFill(['transaction' => $transactionId]))->save();
    }

    /**
     * Set payment's receipt
     *
     * @param string $receipt
     * @return self
     */
    public function setReceipt(string $receipt): self
    {
        return tap($this->forceFill(['receipt' => $receipt]))->save();
    }
}
