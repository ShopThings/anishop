<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;

class Coupon extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * @return int
     */
    public function getUsedCount(): int
    {
        return OrderDetail::query()->where('coupon_code', $this->code)->count();
    }

    /**
     * @param float $price
     * @return bool
     */
    public function canApplyOn(float $price): bool
    {
        if (
            (
                !$this->apply_min_price ||
                ($this->apply_min_price && $price >= $this->apply_min_price)
            ) &&
            (
                !$this->apply_max_price ||
                ($this->apply_max_price && $price <= $this->apply_max_price)
            )
        ) {
            return true;
        }

        return false;
    }
}
