<?php

namespace App\Models;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
        'is_sealed',
    ];

    protected $casts = [
        'type' => PaymentTypesEnum::class,
        'bank_gateway_type' => GatewaysEnum::class,
        'options' => 'encrypted:array',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
        'is_sealed' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }
}
