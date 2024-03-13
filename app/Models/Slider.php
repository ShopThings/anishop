<?php

namespace App\Models;

use App\Enums\Sliders\SliderPlacesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slider extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'place_in' => SliderPlacesEnum::class,
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
        'options' => 'array',
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(SliderItem::class);
    }
}
