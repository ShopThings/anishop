<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
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
