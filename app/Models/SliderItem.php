<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderItem extends Model
{
    use HasCreatedRelationTrait;

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'options' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
