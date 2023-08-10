<?php

namespace App\Models;

use App\Enums\Sliders\SliderPlacesEnum;
use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SliderPlace extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'place_in' => SliderPlacesEnum::class,
        'is_published' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function sliders(): HasMany
    {
        return $this->hasMany(Slider::class);
    }
}
