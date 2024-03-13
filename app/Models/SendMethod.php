<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SendMethod extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'determine_price_by_shop_location' => 'boolean',
        'only_for_shop_location' => 'boolean',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }
}
